// wt cron schedule -n reservascron "* */4 * * *" cron.js -p "wptest-default" \
//   --secret github_token=... \
//   --output url cron.js

var Promise = require('bluebird');
var request = Promise.promisify(require("request"));
var cheerio = require('cheerio');
var _ = require('lodash');
var moment = require('moment');

module.exports = function (context, cb) {

request('http://www.bcra.gov.ar/Estadisticas/estprv010001.asp?descri=1&fecha=Fecha_Serie&campo=Res_Int_BCRA')
        .then(function(response){
          console.log('GET FORM');
          var $ = cheerio.load(response[1]);

          var desde = $('select[name=desde]').find('option')[1].attribs.value;
          var hasta = $('select[name=hasta]').find('option')[1].attribs.value;

          return {
            desde: desde,
            hasta: hasta,
            "I1.x": 34,
            "I1.y": 10,
            I1: 'Enviar',
            fecha: 'Fecha_Serie',
            descri: 1,
            campo: 'Res_Int_BCRA'
          }
        })
        .then(function(params){
          console.log('POST');
          return request({
            method: 'POST',
            uri:"http://www.bcra.gov.ar/Estadisticas/estprv010001.asp",
            form:params
          });
        })
        .then(function(response) {
          console.log('PARSE');
          var $ = cheerio.load(response[1]);

          var data = $('table#texto_columna_2').find('tr')
            .filter(function(i, elem) {
              return i>0;
            })
            .map(function(i, elem) {
              var cols = $(this).find('td');
              var fecha = moment(cols.first().text(), 'DD/MM/YYYY');
              var monto = cols.last().text();
              return { 
                        mes: fecha.format('YYYY-MM-01'),
                        fecha: fecha.format('YYYY-MM-DD'),
                        monto: monto
                      };
            }).get();

          return data;

        })
        .then(function(data){
          console.log(data[0]);
          console.log('PROCESS');

          var processed = {
            historico: data
          }

          processed['ultimos30dias'] = _.slice(data, 0, 30);
          processed['ultimos7dias'] = _.slice(data, 0, 7);
          processed['ultimos12meses'] = _.filter(data,function(ele) {
            
            return moment(ele.mes, 'YYYY-MM-DD').isAfter(moment().month(-12));

          });

          processed['ultimos12meses'] = _.groupBy(processed['ultimos12meses'], function(ele) {
            return ele.mes;
          });

          processed['ultimos12meses'] = _.map(processed['ultimos12meses'], function(ele) {
            return {
              fecha: ele[0].mes,
              monto: ele[0].monto
            };
          });

          processed['ultimos7dias'] = _.sortBy(processed['ultimos7dias'], 'fecha');
          processed['ultimos30dias'] = _.sortBy(processed['ultimos30dias'], 'fecha');
          processed['ultimos12meses'] = _.sortBy(processed['ultimos12meses'], 'fecha');
          processed['historico'] = _.sortBy(processed['historico'], 'fecha');

          return processed;

        })
        .then(function(data) {
          console.log('GET SHA');

          request({
            uri:'https://api.github.com/repos/glena/reservashoy/git/trees/gh-pages',
            headers : {
              "User-Agent":"reservashoy cron"
            }
          })
            .then(function(response){
              response = JSON.parse(response[1]);

              var file = _.find(response.tree, function(e) { return e.path == 'data.json'; })

              return file.sha;
            })
            .then(function(sha){
              console.log('UPDATE');

              var json_data = JSON.stringify(data);

              var username = "glena",
                token = context.data.github_token,
                auth = "Basic " + new Buffer(username + ":" + token).toString("base64");

              return request({
                method: 'PUT',
                uri:"https://api.github.com/repos/glena/reservashoy/contents/data.json",
                headers : {
                  "Authorization" : auth,
                  "User-Agent":"reservashoy cron"
                },
                json:{
                  "message": "Updated data.json",
                  "branch":"gh-pages",
                  "committer": {
                    "name": "German Lena CRON",
                    "email": "german.lena@gmail.com"
                  },
                  "content": new Buffer(json_data).toString("base64"),
                  "sha": sha
                }
              });
            })
            .then(function(response){
              console.log('DONE', response[1]);
              cb(null, 'DONE');
            })
            .catch(function(e) {
                console.error(e);
            });

        });

}

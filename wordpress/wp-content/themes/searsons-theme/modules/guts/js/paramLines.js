/**
 * 
 * Deal with param line
 * 
 */

export const getParams = () => {
    var url = window.location.href;
    var params = url.split('?');
    if(params.length > 1){
      var param = params[1];
      var params = param.split('&');
      var paramObj = {};
      params.forEach(function(item){
        var param = item.split('=');
        paramObj[param[0]] = param[1];
      });
      return paramObj;
    } else {
      return false;
    }
  }
  
export const removeParam = (key, sourceURL) => {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
  }
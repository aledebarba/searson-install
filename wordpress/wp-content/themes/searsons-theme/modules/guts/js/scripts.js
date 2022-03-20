import { friendlyName } from './dictionary_friendlyNames.js';
import { searchForm } from './js/searchForm.js';

document.addEventListener(
    "DOMContentLoaded",
    () => {
      // resolve grid radio selectables
      const list = document.querySelectorAll(".input_radio-group");
      [...list].forEach( (item) => {
        item.addEventListener(
          "click",
          ()=>{            
            let radio = item.querySelector('input');
            let name = radio.getAttribute('name');
            let value = radio.getAttribute('value');
            let radioList = [...document.forms['gutshopifyelt__search-form'][name]]            
            radioList.forEach((docRadio)=>{
              if(docRadio.value == value) { 
                docRadio.checked = !docRadio.checked;
              };              
            })
            updateForm(list); 
          },        
          false
        );
      });
  
      //resolve accordeons
      const accordions = document.querySelectorAll(".accordion__title");
      [...accordions].forEach((accordion) => {
        accordion.addEventListener(
          "click",
          (element) => {
            accordion.parentNode.classList.toggle("open");
          },
          false
        );
      });

      // resolve button to reset all filters options 
      document.getElementById('form-reset-button').addEventListener('click', () => {
        guts__clearForm();
      })

      // add close interaction to modal/search overlay close button
      document.querySelector('.guts__filter_overlay>.close-button').addEventListener('click', () => {
        document.querySelector('.guts__filter_overlay').classList.add('closed');
        document.querySelector('body').classList.remove('active');
        [...document.querySelectorAll('div.accordion')].forEach((accordion) => {
          accordion.classList.remove('open');
        })
      })

      // recover form state and render pagination
      formState();
      searchState(); // render current search selected filters and respective buttons
      renderPagination();

    },
    false
  ); // onDOMload


function guts__clearForm() {
  let accordionTitles = document.querySelectorAll(`span.current__selection`);
  [...accordionTitles].forEach((title) => { title.innerHTML = "" });
  let form = document.forms['gutshopifyelt__search-form'];
  window.history.pushState({}, document.title, window.location.pathname);
  form.reset();
  updateForm();
  formState();
  searchState();
}

function updateForm(radioGroups) {
  radios = document.querySelectorAll(".input_radio-group");

  // clear all current selections values
  let accordionTitles = document.querySelectorAll(`span.current__selection`);
  [...accordionTitles].forEach((title) => { title.innerHTML = "" });
  
  [...radios].forEach((group) => {
    let span = group.querySelector('span');
    let radio = group.querySelector('input');
    let name = radio.getAttribute('name');
    let value = radio.getAttribute('value');

    
    if (radio.checked) {
      
      let accordionTitle = document.querySelector(`span[for='${name}']`);
      if (accordionTitle) { 
        let friendlyValueName = friendlyName.find(item => item.field == value);
        if(friendlyValueName) {
          accordionTitle.innerHTML = friendlyValueName.label;
        } else {
          accordionTitle.innerHTML = value;
        }
      }
      span.style.cssText = `
        background-color: var(--color-brand) !important;
        color: white !important;
        border-color: var(--color-brand) !important;
        `;
    } else {
      span.style.cssText = `
        background-color: white !important;
        color: black !important;
        border-color: black !important;
        `
    }
  })
}

function formState(options) {
  let params = getParam();
  for(var key in params) {
    let value = params[key];
    let input = document.querySelector(`input[name='${key}'][value='${value}']`);
    if(input) {
      input.checked = true;
      let span = document.querySelector(`span[for='${key}']`);
      if(span) {
        span.innerHTML = params[key];
      }
    }
  }
  updateForm();
}
/**
 * 
 * Render search selected items 
 * 
 */
function searchState() {
  
  let searchLi =  [
    { name: 'type', label: 'Type', rel: 'type', class: 'search-filter-type' },
    { name: 'Colour', label: 'Colour', rel: 'colour', class: 'search-filter-colour' },
    { name: 'Country', label: 'Country', rel: 'country', class: 'search-filter-country' },
    { name: 'Maturity', label: 'Maturity', rel: 'maturity', class: 'search-filter-maturity' },
    { name: 'price-range', label: 'Price', rel: 'price', class: 'search-filter-price' },
    { name: 'Grape', label: 'Grape', rel: 'grape', class: 'search-filter-grape' },
    { name: 'Region', label: 'Region', rel: 'region', class: 'search-filter-region' },
    { name: 'Organic', label: 'Organic', rel: 'organic', class: 'search-filter-organic' },
    { name: 'Vegan', label: 'Vegan', rel: 'vegan', class: 'search-filter-vegan' },
    { name: 'Sweetness-range', label: 'Sweetness', rel: 'sweetness', class: 'search-filter-sweetness' },
  ]
  


  let params = getParam();

  // page elements to render into
  let divSearchFilters = document.getElementById('guts__current-applied-filters__list');
  let divSearchValues = document.getElementById('guts__current-applied-filters__values');
  
  //clear content
  divSearchFilters.innerHTML = '';
  divSearchValues.innerHTML = '';

  // render filter values (actual selected values)
  for (const key in params) {
    
    let value = params[key];
    let foundFriendlyName = false;
    let liValue = document.createElement('li');
      if (value) {
        friendlyName.forEach((friendly) => {
          if(friendly.field === value) {
            liValue.innerHTML = friendly.label;
            foundFriendlyName = true;
          }
        })
        if(!foundFriendlyName) {
          liValue.innerHTML = value;
        }
      }
      
      //liValue.innerHTML = value ? friendlyName.forEach((param) => { if(param.field === key) { return param.label } }) : '';
      liValue.classList.add('guts__selected-filter-value', 'button', 'small', 'icon-end', 'default', 'round');
      divSearchValues.appendChild(liValue);      
  }
  
  // current filter selection buttons
  let searchFilters = document.getElementById('guts__current-applied-filters__list');
  
  searchLi.forEach((item) => {
    let value = params ? params[item.name] : false;
    let selected = value ? "guts__filter__selected" : "";
    let selectedStyle = value ? "background-color: var(--color-brand-light) !important; color: white !important; border-color: transparent !important; border-radius: .25em !important;" : "";
    let li = document.createElement('li');
      li.classList.add('filterby-taxonomy',item.class);
      li.setAttribute('rel', item.rel);
      li.style.cssText = selectedStyle;
      li.innerHTML = `<a href="" class="button small round line ${selected}" style="${selectedStyle}">
        <span class="label">${item.label}</span>
      </a>`;
      li.addEventListener('click', (e) => {
        e.preventDefault();
        let overlay = document.querySelector('div.guts__filter_overlay');
        let relatedAccordion = document.querySelector('div.accordion.' + item.rel);        
        if (relatedAccordion) {
          relatedAccordion.classList.add('open');
        }
        overlay.classList.remove('closed');
        document.querySelector('body').classList.add('active');
      })
      divSearchFilters.appendChild(li); 
  })

  // add last li -> see all filters
  let li = document.createElement('li');
  li.classList.add('all-filters','filterby-taxonomy');
  li.innerHTML = `<a href="" class="button small round icon-end default">
                    <span class="label">All Filters</span> 
                    <span class="icon"><i class="fal fa-sliders-h"></i></span> 
                  </a>`;
  li.addEventListener('click', (e) => {
    e.preventDefault();
    let overlay = document.querySelector('div.guts__filter_overlay');
    overlay.classList.remove('closed');
  });
  divSearchFilters.appendChild(li);
}


/**
 * 
 * render pagination based on div.guts__pagination
 * and get param line
 * 
 */
function renderPagination() {
  let divPagination = document.getElementById('guts__pagination-hold');
  let total = document.getElementById('guts__data-reference').getAttribute('data-total-found');
  let perPage = document.getElementById('guts__data-reference').getAttribute('data-per-page');
  let totalPages = Math.ceil(total / perPage);
  
  // let paginationFirst = 'First Page';
  // let paginationLast = 'Last Page';
  
  let currentPage = document.getElementById('guts__data-reference').getAttribute('data-page');
  let curPage = parseInt(getParam()['current-page']);
  curPage = curPage ? curPage : 1;
  let nextPage = curPage < totalPages ? curPage + 1 : totalPages;
  let paginationPrev = curPage > 1 ? '' : '';
  let paginationNext = curPage < totalPages ? '' : '';
  
  let prevPage = curPage > 1 ? curPage - 1 : 1;
  let pagesToShow = 4;
  let bottom = curPage > pagesToShow ? curPage - pagesToShow : 1;
  let top = curPage < totalPages - pagesToShow ? curPage + pagesToShow : totalPages;
  let pagination = ``;

  if (totalPages > 1) {
    pagination += `<div class="guts__pagination-prev pagination-submit" page="${prevPage}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M148.7 411.3l-144-144C1.563 264.2 0 260.1 0 256s1.562-8.188 4.688-11.31l144-144c6.25-6.25 16.38-6.25 22.62 0s6.25 16.38 0 22.62L54.63 240H496C504.8 240 512 247.2 512 256s-7.156 16-16 16H54.63l116.7 116.7c6.25 6.25 6.25 16.38 0 22.62S154.9 417.6 148.7 411.3z"/></svg> ${paginationPrev}</div>`;
    
    for(let pages = 1; pages <= totalPages; pages++) {
      if (pages == curPage) {
        pagination += `<div class="guts__pagination-current">${pages}</div>`;
      } else {
        if (pages >= bottom && pages <= top) {
          pagination += `<div class="guts__pagination-page pagination-submit" page="${pages}">
            ${pages}
          </div>`;
        }
      }
    }

    pagination += `<div class="guts__pagination-next pagination-submit" page="${nextPage}">${paginationNext} <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M443.7 266.8l-165.9 176C274.5 446.3 269.1 448 265.5 448c-3.986 0-7.988-1.375-11.16-4.156c-6.773-5.938-7.275-16.06-1.118-22.59L393.9 272H16.59c-9.171 0-16.59-7.155-16.59-15.1S7.421 240 16.59 240h377.3l-140.7-149.3c-6.157-6.531-5.655-16.66 1.118-22.59c6.789-5.906 17.27-5.469 23.45 1.094l165.9 176C449.4 251.3 449.4 260.7 443.7 266.8z"/></svg></div>`;
  }
  pagination += '';
  divPagination.innerHTML = pagination;

  /**
  * 
  * resolve pagination click events sumbitting the actual form state plus the page number
  *
  */
  [...document.querySelectorAll('.pagination-submit')].forEach((item) => {
    item.addEventListener('click', (e) => {
      e.preventDefault;
      let form = document.getElementById('gutshopifyelt__form');
      var inputHidden = document.getElementById('guts__pagination-current-page-hidden');
      if (!inputHidden) {
        inputHidden = document.createElement('input');
        inputHidden.setAttribute('type', 'hidden');
        inputHidden.setAttribute('name', 'current-page');
        inputHidden.setAttribute('id', 'guts__pagination-current-page-hidden');
        form.appendChild(inputHidden);
      }
      inputHidden.setAttribute('value', item.getAttribute('page').trim()); 
      form.submit();
    })
  })
}

function getParam(){
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

function removeParam(key, sourceURL) {
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


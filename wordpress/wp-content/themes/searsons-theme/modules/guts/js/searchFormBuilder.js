import { getParams } from "./paramLines.js";
import { getFriendlyName } from "./dictionary_friendlyNames.js";


export function buildForm(data) {

    let priceRange = buildRadioAccordion( "Price", "price-range", "price-range", "", [
        "price-f0-t99999",
        "price-f0-t15",
        "price-f15-t25",
        "price-f25-t50",
        "price-f50-t100",
        "price-f100-t99999"
    ], false)
    let sweetnessRange = buildRadioAccordion( "Sweetness", "sweetness-range", "sweetness-range", "", [
            "sweetness-f0-t99999",
            "sweetness-f0-t1",
            "sweetness-f1-t5",
            "sweetness-f5-t7",
            "sweetness-f7-t99999"
    ])
    let otherOptions = buildCheckboxAccordion( "Other Options", "other-options", "other-options", "", [ "organic", "vegan" ] );
    let sortOrder = buildSelectAccordion( "Sort Order", "sort-order", "sort-order", "", [
        "sort-default",
        "title-asc",
        "title-desc",
        "price-asc",
        "price-desc",
        "created-asc"
    ], false )
    
    const [ types, country, maturity, grape, colour, sweetness, abv ] = searchFieldsTypes( [ "type", "metakey-Country", "metakey-Maturity", "metakey-Grape", "metakey-Colour", "metakey-Sweetness", "metakey-ABV" ], data );

    let html = `
    <div id='gutshopifyelt'>
        <div class='guts__filter_overlay closed'>
            <div class='close-button'>
                <i class="fal fa-times"></i>
            </div>
            <form id='gutshopifyelt__form' class='multi-select-form' method="GET" action="" name="gutshopifyelt__search-form">
                ${buildRadioAccordion( "Types", "types", "types", "", types.values )}
                ${buildRadioAccordion( "Countries", "countries", "countries", "", country.values )}
                ${buildRadioAccordion( "Maturity", "maturity", "maturity", "", maturity.values )}
                ${buildRadioAccordion( "Grapes", "grapes", "grapes", "", grape.values )}
                ${buildRadioAccordion( "Colours", "colours", "colours", "", colour.values )}
                ${sweetnessRange}
                ${buildRadioAccordion( "ABV", "abv", "abv", "", abv.values )}
                ${priceRange}
                ${otherOptions}
                ${sortOrder}
                <!-- submit -->
                <div class="guts__filter_overlay__buttons bottom">
                    <input type="reset" value="Clear Options" id="form-reset-button" class="button line font-weight-medium">
                    <input type="submit" value="Filter" class="button default text-transform font-weight-bold">
                </div>
            </form>
        </div>
    </div>
    `
    let element = document.getElementById("guts-js-render-filter-form");
    element.innerHTML = html;
    return html;

}
/**
 * 
 * Seach inside the data object and return grouped data sets
 * 
 * @param {[] array of data name to lookfor inside data} groups 
 * @param {json data to look groups} data 
 * @returns array of groups
 * 
 * @example const [ names, emails ] = searchFieldsTypes( [ "name", "email" ], data )
 * 
 */
const searchFieldsTypes = ( groups, data ) => {

    var res = [];
    
    groups.forEach( group => {
        res.push({ key: group, values: new Set} );
    })
    let k=0;

    data.forEach( product => {
        k++
        if (k<10) console.log(product);
        res.forEach( item => {
            if ( item.key in product && product[item.key] !== "" ) {
                let itemValue = item.key === "metakey-Maturity" ? `maturity-${product[item.key]}` : product[item.key];
                item.values.add( itemValue );
            }
        });
    });

    return (res)
}
/**
 * 
 * Render a radio group accordion
 * 
 * @param {string} accordion title 
 * @param {string} radio group name 
 * @param {string} classes to apply to the accordion
 * @param {string} icon class to apply to the accordion
 * @param {[]} radioArray array of radio options
 * @param {boolean} sort (default) true if the accordion should be sorted
 * @returns 
 * 
 */
export const buildRadioAccordion = (  title = "Options", name = "radio_group", classes = "", icon = "", radioArray = [], sort = true ) => {
    let valuesArray = Array.from(radioArray);
    if (sort) { valuesArray.sort(); }
    let html = `
        <div class='accordion'>
            <div class='accordion__title'>
                <h1>${title}</h1>
                <span class='current__selection' for='${name}'></span>
                <div class='icon'>${icon}</div>
            </div>
            <div class='radio_list'>
            `
    valuesArray.forEach( ( value ) => {
        html += `
            <div class='input_radio-group ${name}'>
                <label for='${value}'>
                    <input type='radio' name='${name}' value='${getFriendlyName(value)}' class='list-item'>
                    <span for='${name}'>${getFriendlyName(value)}</span>
                </label>
            </div>
            `
    })
    html += `</div>
    </div>`
    return html    
}
/**
 * 
 * Render a group of checkbox
 * 
 * @param {string} accordion title 
 * @param {string} radio group name 
 * @param {string} classes to apply to the accordion
 * @param {string} icon class to apply to the accordion
 * @param {[]} checkArray array of checkboxes
 * @param {boolean} sort (default) true if the accordion should be sorted
 * @returns 
 * 
 */
export const buildCheckboxAccordion = (  title = "Options", name = "checkbox_group", classes = "", icon = "", checkArray = [], sort = true ) => {
    let valuesArray = Array.from(checkArray);
    if (sort) { valuesArray.sort(); }
    let html = `
        <div class='accordion'>
            <div class='accordion__title'>
                <h1>${title}</h1>
                <span class='current__selection' for='${name}'></span>
                <div class='icon'>${icon}</div>
            </div>
            <div class='checkbox_list'>
            `
    valuesArray.forEach( ( value ) => {
        html += `
            <div class='input_checkbox-group ${name}'>
                <label for='${value}'>
                    <input type='checkbox' name='${name}' value='${value}' class='list-item hceckbox_item'>
                    <span for='${name}'>${getFriendlyName(value)}</span>
                </label>
            </div>
            `
    })
    html += `</div>
    </div>`
    return html    
}
/**
 * 
 * Render a select group
 * 
 * @param {string} accordion title 
 * @param {string} radio group name 
 * @param {string} classes to apply to the accordion
 * @param {string} icon class to apply to the accordion
 * @param {[]} optionsArray array of select options
 * @param {boolean} sort (default) true if the accordion should be sorted
 * @returns 
 * 
 */
export const buildSelectAccordion = (  title = "Options", name = "select_group", classes = "", icon = "", optionsArray = [], sort = true ) => {
    let valuesArray = Array.from(optionsArray);
    if (sort) { valuesArray.sort(); }
    let html = `
        <div class='accordion'>
            <div class='accordion__title'>
                <h1>${title}</h1>
                <span class='current__selection' for='${name}'></span>
                <div class='icon'>${icon}</div>
            </div>
            <div class='select_list'>
            <label for='${name}'>${name}</label>
            <select name='${name}' id='${name}'>
            `
    valuesArray.forEach( ( value ) => { html += `<option value='${value}'>${getFriendlyName(value)}</option>` })
    html += `
            </select>
        </div>
    </div>`
    return html    
}

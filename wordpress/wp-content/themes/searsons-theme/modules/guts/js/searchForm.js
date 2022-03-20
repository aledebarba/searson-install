import { priceRange } from "./searchForm_priceRange.js"
import { sweetnessRange } from "./searchForm_sweetnessRange.js"
import { otherOptions } from "./searchForm_OtherOptions.js"
import { sortOrder } from "./searchForm_sortOrder.js"
import { getParams } from "./paramLines.js"

const typesList = ``
const countrieList = ``
const maturitiesList = ``
const grapesList   = ``
const regionsList  = ``

export const searchForm = ( data ) => {

let param = getParam();
// let data = 
let html = `
    <div id='gutshopifyelt'>
        <div class='guts__filter_overlay closed'>
            <div class='close-button'>
                <i class="fal fa-times"></i>
            </div>
        
            <form id='gutshopifyelt__form' class='multi-select-form' method="GET" action="" name="gutshopifyelt__search-form">
                ${typesList}
                ${countrieList}
                ${maturitiesList}
                ${grapesList}
                ${regionsList}
                ${sweetnessRange}
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
}
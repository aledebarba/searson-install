/**
 * 
 * this is part of the guts module -> dynamic form filter
 * 
 */
export const sweetnessRange = () => { return (`
<!-- Sweetness range radio pickers -->
<div class='accordion'>
    <div class='accordion__title'>
        <h1>Sweetness</h1>
        <span class='current__selection' for='sweetness-range'></span>
        <div class='icon'></div>
    </div>
    <div class='radio_list'>                    
        <div class='input_radio-group sweetness-range'>
        <label for='sweetness-f0-t99999'>
            <input type='radio' name='sweetness-range' value='sweetness-f0-t99999' class='list-item'>
            <span>All</span>
        </label>
        </div>
        <div class='input_radio-group sweetness-range'>
        <label for='sweetness-f0-t1'>
            <input type='radio' name='sweetness-range' value='sweetness-f0-t1' class='list-item'>
            <span>Dry</span>
        </label>
        </div>
        <div class='input_radio-group sweetness-range'>
        <label for='sweetness-f1-t5'>
            <input type='radio' name='sweetness-range' value='sweetness-f1-t5' class='list-item'>
            <span>Off Dry</span>
        </label>
        </div>
        <div class='input_radio-group sweetness-range'>
        <label for='sweetness-5-t7'>
            <input type='radio' name='sweetness-range' value='sweetness-f5-t7' class='list-item'>
            <span>Medium Sweet</span>
        </label>
        </div>
        <div class='input_radio-group sweetness-range'>
        <label for='sweetness-f7-t99999'>
            <input type='radio' name='sweetness-range' value='sweetness-f7-t99999' class='list-item'>
            <span>Sweet</span>
        </label>
        </div>
    </div>
</div>
<!-- end sweetness range radio pickers -->
`)};

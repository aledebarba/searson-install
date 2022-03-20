export const priceRange = () => { return (
`<!-- Price range radio pickers -->
<div class='accordion'>
    <div class='accordion__title'>
        <h1>Price</h1>
        <span class='current__selection' for='price-range'></span>
        <div class='icon'></div>
    </div>
    <div class='radio_list'>                    
      <div class='input_radio-group price-range'>
        <label for='price-f0-t99999'>
          <input type='radio' name='price-range' value='price-f0-t99999' class='list-item'>
          <span>All</span>
        </label>
      </div>
      <div class='input_radio-group price-range'>
        <label for='price-f0-t15'>
          <input type='radio' name='price-range' value='price-f0-t15' class='list-item'>
          <span>€1 to €15</span>
        </label>
      </div>
      <div class='input_radio-group price-range'>
        <label for='price-f15-t25'>
          <input type='radio' name='price-range' value='price-f15-t25' class='list-item'>
          <span>€15 to €25</span>
        </label>
      </div>
      <div class='input_radio-group price-range'>
        <label for='price-f25-t50'>
          <input type='radio' name='price-range' value='price-f25-t50' class='list-item'>
          <span>€25 to €50</span>
        </label>
      </div>
      <div class='input_radio-group price-range'>
        <label for='price-f50-t100'>
          <input type='radio' name='price-range' value='price-f50-t100' class='list-item'>
          <span>€50 to €100</span>
        </label>
      </div>
      <div class='input_radio-group price-range'>
        <label for='price-f100-t99999'>
          <input type='radio' name='price-range' value='price-f100-t99999' class='list-item'>
          <span>€100 or more</span>
        </label>
      </div>
    </div>
</div>
<!-- end Price range radio pickers -->
`
)};

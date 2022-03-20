export const otherOptions = () => { return (
    `<!-- Organic / Vegan checkboxes -->
    <div class='accordion'>
      <div class='accordion__title'>
          <h1>Organic / Vegan</h1>
          <span class='current__selection' for='organic-vegan'></span>
          <div class='icon'></div>
      </div>
      <div class='checkbox__list'>
        <div class='input__checkbox-group organic'>
          <label for='organic'>
            <input type='checkbox' name='organic' value='true' class='checkbox-item'>
            <span>Organic</span>
          </label>
        </div>
        <div class='input_checkbox-group vegan'>
          <label for='vegan'>
            <input type='checkbox' name='vegan' value='true' class='list-item'>
            <span>Vegan</span>
          </label>
        </div>
      </div>
    </div>
    <!-- end Organic / Vegan checkboxes -->`
)}

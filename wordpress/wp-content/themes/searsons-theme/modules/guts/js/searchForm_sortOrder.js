export const sortOrder = () => {
    return (
        `<!-- sort -->
    <div class="accordion">
        <div class='accordion__title'>
            <h1>Sort order</h1>
            <span class='current__selection' for='sortby'></span>
            <div class='icon'></div>
        </div>
        <label for="sortby">Sort by:</label>
        <select name="sortby">
            <option value="title-asc">Title A-Z</option>
            <option value="title-desc">Title Z-A</option>
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
            <option value="date-asc">Recently Added</option>
        </select>
    </div>
    <!-- /sort -->`
    );
};

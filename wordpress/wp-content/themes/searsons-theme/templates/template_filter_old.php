<!-- filter overlay -->
<div class="filter-modal" id="search__overlay">
    <aside class="filter-sidebar">
        <div class="box-close">
            <div class="icon" onclick="closeOverlays()">
                <i class="fal fa-times"></i>
            </div>
        </div>

        <div class="box-items">
            <!-- collections -->
            <div class="item collections">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Collections</div>
                    </div>
                    
                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-collections">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /collections -->

            <!-- price -->
            <div class="item price">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Price</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-price">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                                
                                <?php /* andre will add the prices */ ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /price -->

            <!-- categories -->
            <?php // andre vai passar pra select ?>
            <div class="item categories">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Categories (Type)</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-categories">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /categories -->

            <!-- country -->
            <div class="item country">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Country</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-country">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- / -->

            <!-- region -->
            <div class="item region">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Region</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-region">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /region -->

            <!-- maturity -->
            <div class="item maturity">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Maturity</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-maturity">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /maturity -->

            <!-- sweetness -->
            <div class="item sweetness">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Sweetness</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-sweetness">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /sweetness -->

            <!-- grape -->
            <div class="item grape">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Grape</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-grape">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /grape -->

            <!-- colour -->
            <div class="item colour">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Colour</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-colour">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /colour -->

            <!-- organic -->
            <div class="item organic">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Organic</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-organic">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /organic -->

            <!-- vegan -->
            <div class="item vegan">
                <div class="collapsable-div">
                    <div class="headline">
                        <div class="h5 font-weight-bold text-transform">Vegan</div>
                    </div>

                    <?php // this button and the next div MUST STAY TOGETHER ?>
                    <button id="" class="button line collapsable-div-toggler">
                        <span class="icon"><i class="far fa-plus"></i></span>
                    </button>
                    <div class="collapsable-content">
                        <ul id="query-vegan">
                            <li>
                                <label for="">
                                    <input type="radio" name="" id="">
                                    <span></span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /vegan -->

            <div id="overlay-search__alert-messages">Message</div>
        </div>

        <div class="box-buttons">
            <button class="search__button button large line icon-end font-weight-bold" onclick="clearFilters()">
                <span class="label">Clear Filters</span>
            </button>
            <button class="search__button button large default icon-end text-transform font-weight-bold" onclick="applyFilters()">
                <span class="label">Apply Filters</span>
            </button>
        </div>
    </aside>
</div>
<!-- filter overlay -->
        <h2>{lang_CustomFilters}</h2>
        <div class="filter-checkbox-container">
        
            <div class="row">
                <div class="span12">
                    <input style="" option_id="19" type="text" class="span12 input_am id_19" placeholder="{options_name_19}" />
                    <input style="" option_id="20" type="text" class="span12 input_am id_20" placeholder="{options_name_20}" />
                </div>
                <div class="span12">
                    <input style="" option_id="36" type="text" rel="from" class="span12 input_am_from id_36_from DECIMAL" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" />
                </div>
                <div class="span12">
                    <input style="" option_id="36" type="text" rel="to" class="span12 input_am_to id_36_to DECIMAL" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" />
                </div>
                
                <?php if(config_db_item('search_energy_efficient_enabled') === TRUE): ?>
                <div class="span12">
                <select option_id="59" rel="to" class="span12 nomargin input_am_to id_59_to" placeholder="{options_name_59}">
                    <option value="">{options_name_59}</option>
                    <option value="50">A</option>
                    <option value="90">B</option>
                    <option value="150">C</option>
                    <option value="230">D</option>
                    <option value="330">E</option>
                    <option value="450">F</option>
                    <option value="999999">G</option>
                </select>
                </div>
                <?php endif; ?>
                
            </div>

            <div class="row filter-checkbox-row">
                <div class="span6">
                    <label class="checkbox">
                    <input option_id="11" class="checkbox_am" type="checkbox" value="true{options_name_11}" />{options_name_11}
                    </label>
                    <label class="checkbox">
                    <input option_id="22" class="checkbox_am" type="checkbox" value="true{options_name_22}" />{options_name_22}
                    </label>
                    <label class="checkbox">
                    <input option_id="25" class="checkbox_am" type="checkbox" value="true{options_name_25}" />{options_name_25}
                    </label>
                    <label class="checkbox">
                    <input option_id="27" class="checkbox_am" type="checkbox" value="true{options_name_27}" />{options_name_27}
                    </label>
                    <label class="checkbox">
                    <input option_id="28" class="checkbox_am" type="checkbox" value="true{options_name_28}" />{options_name_28}
                    </label>
                </div>
                <div class="span6">
                    <label class="checkbox">
                    <input option_id="29" class="checkbox_am" type="checkbox" value="true{options_name_29}" />{options_name_29}
                    </label>
                    <label class="checkbox">
                    <input option_id="32" class="checkbox_am" type="checkbox" value="true{options_name_32}" />{options_name_32}
                    </label>
                    <label class="checkbox">
                    <input option_id="30" class="checkbox_am" type="checkbox" value="true{options_name_30}" />{options_name_30}
                    </label>
                    <label class="checkbox">
                    <input option_id="33" class="checkbox_am" type="checkbox" value="true{options_name_33}" />{options_name_33}
                    </label>
                    <label class="checkbox">
                    <input option_id="23" class="checkbox_am" type="checkbox" value="true{options_name_23}" />{options_name_23}
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="span12">
                <button type="submit" class="btn span12 refresh_filters">{lang_RefreshResults}</button>
                </div>
            </div>
        </div>
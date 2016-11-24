<template id="animals-widget-template">
    <div>
        <div v-for="animal in animals">
            <div :class="wrapperClasses">

                <div v-bind:id="'modal_just_fed_' + animal.id" class="modal">
                    <form v-bind:action="'/api/v1/animals/' + animal.id + '/add_feeding'" data-method="POST" onsubmit="$(this).submit(window.submit_form)">
                        <div class="modal-content">
                            <h4>@lang('labels.just_fed')</h4>
                            <p>
                                <select name="meal_type">
                                    <option value="crickets">@choice('labels.cricket', 2)</option>
                                    <option value="mixed_fruits">@lang('labels.mixed_fruits')</option>
                                    <option value="beatle_jelly">@lang('labels.beatle_jelly')</option>
                                </select>
                                <label for="meal_type">@lang('labels.meal_type')</label>
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light terrarium-card-image"
                         v-bind:class="animal.default_background_filepath ? '' : 'teal darken-1'"
                         v-bind:style="animal.default_background_filepath ? 'background-image: url(\'' + animal.default_background_filepath + '\');' : ''">
                    </div>

                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4 truncate">
                            <span>@{{ animal.display_name }}</span>
                            <i class="material-icons right">more_vert</i>
                        </span>
                        <p>
                            <span v-show="animal.latin_name">@{{ animal.latin_name }}<br /></span>
                            <span v-show="animal.common_name">@{{ animal.common_name }}<br /></span>
                            <span v-show="animal.birth_date !== null">@{{ animal.birth_date }}</span>
                            <span v-show="animal.death_date !== null"> - @{{ animal.death_date }}</span>
                            <span v-show="animal.birth_date || animal.death_date"><i>@{{ animal.age }}</i></span>
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/animals/' + animal.id">@lang('buttons.details')</a>
                        <a v-bind:href="'/animals/' + animal.id + '/edit'">@lang('buttons.edit')</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">@choice('components.terraria', 1)<i class="material-icons right">close</i></span>
                        <p>
                            <a v-if="animal.terrarium" v-bind:href="'/terraria/' + animal.terrarium.id">@{{ animal.terrarium.display_name }}</a>
                        </p>

                        <span class="card-title grey-text text-darken-4">@choice('labels.actions', 2)</span>
                        <p>
                            <a class="waves-effect waves-teal btn" v-bind:href="'#modal_just_fed_' + animal.id" v-bind:onclick="'$(\'#modal_just_fed_' + animal.id + '\').modal(); $(\'#modal_just_fed_' + animal.id + ' select\').material_select(); $(\'#modal_just_fed_' + animal.id + '\').modal(\'open\');'">@lang('labels.just_fed')</a>
                        </p>
                        <p>
                            <a class="waves-effect waves-teal btn">@lang('labels.just_irrigated')</a>
                        </p>
                        <p>
                            <a class="waves-effect waves-teal btn">@lang('labels.add_weight')</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
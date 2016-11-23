<template id="animals-widget-template">
    <div>
        <div v-for="animal in animals">
            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light terrarium-card-image"
                         v-bind:class="animal.default_background_filepath ? '' : 'teal darken-1'"
                         v-bind:style="animal.default_background_filepath ? 'background-image: url(\'' + animal.default_background_filepath + '\');' : ''">
                    </div>

                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4 truncate">
                            <span>@{{ animal.display_name }} <i class="material-icons">@{{ animal.gender_icon }}</i></span>
                            <i class="material-icons right">more_vert</i>
                        </span>
                        <p>
                            <span>@{{ animal.latin_name }}</span><br />
                            <span>@{{ animal.common_name }}</span><br />
                            <span>@{{ animal.birth_date }} <i>@{{ animal.age }}</i></span>
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/animals/' + animal.id">@lang('buttons.details')</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
                        <p>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
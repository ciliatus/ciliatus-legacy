<template id="animals-widget-template">
    <div v-for="animal in animals">
        <div class="x_panel">

            <div class="x_title">
                <h2>
                    <i class="material-icons">pets</i> <a href="/animals/@{{ animal.id }}">@{{ animal.display_name }}</a>
                    <i class="fa fa-@{{ animal.gender_icon }}"></i>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/animals/@{{ animal.id }}/edit"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="/animals/@{{ animal.id }}/delete"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <span v-if="animal.birth_date !== null">
                                <strong>*</strong> @{{ animal.birth_date }} <span v-if="animal.death_date === null">(@{{ animal.age }})</span>
                            </span>

                            <span v-if="animal.death_date !== null">
                                <strong>†</strong> @{{ animal.death_date }} (@{{ animal.age }})
                            </span>

                            <br />

                            <strong>@lang('labels.common_name'): </strong><span>@{{ animal.common_name }}</span><br />
                            <strong>@lang('labels.latin_name'): </strong><span>@{{ animal.latin_name }}</span><br />
                            <div v-if="animal.terrarium">
                                <strong>@choice('components.terraria', 1): </strong><span><a href="/terraria/@{{ animal.terrarium.id }}">@{{ animal.terrarium.display_name }}</a></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
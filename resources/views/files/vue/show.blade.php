<template id="files-widget-template">
    <div class="card">
        <div class="card-content light-blue darken-1 white-text">
            @{{ files.length }} @choice('components.files', 2)
        </div>

        <div class="card-content">
            <span class="card-title activator truncate">
                <span>@choice('components.files', 2)</span>
                <i class="material-icons right">more_vert</i>
            </span>
            <p>
                <div class="chip" v-for="file in files">
                    <i class="material-icons">insert_drive_file</i>
                    <a v-bind:href="'/files/' + file.id">@{{ file.display_name }}</a> <i>@{{ file.size }}</i>
                </div>
            </p>
        </div>

        <div class="card-action">
            <a v-bind:href="'/files/create?preset[belongsTo_type]=' + belongsTo_type + '&preset[belongsTo_id]=' + belongsTo_id">@lang('buttons.add')</a>
            <a v-bind:href="'/files/?filter[belongsTo_type]=' + belongsTo_type + '&filter[belongsTo_id]=' + belongsTo_id">@lang('buttons.details')</a>
        </div>

        <div class="card-reveal">
            <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
            <p>

            </p>
        </div>
    </div>
</template>
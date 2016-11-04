import Echo from "laravel-echo"

window.echo = new Echo({
    broadcaster: 'pusher',
    key: '0c8933e7ceb3ed05bc9c',
    namespace: 'App\\Events',
    cluster: 'eu'
});

echo.private('dashboard-updates')
    .listen('CriticalStateCreated', (e) => {
        window.dashboardVue.addCriticalState(e);
    })
    .listen('CriticalStateDeleted', (e) => {
        window.dashboardVue.removeCriticalState(e);
    })
    .listen('TerrariumUpdated', (e) => {
        window.dashboardVue.updateTerrarium(e);
    })
    .listen('TerrariumDeleted', (e) => {
        window.dashboardVue.deleteTerrarium(e);
    })
    .listen('AnimalUpdated', (e) => {
        window.dashboardVue.updateAnimal(e);
    })
    .listen('AnimalDeleted', (e) => {
        window.dashboardVue.deleteAnimal(e);
    })
    .listen('ActionSequenceScheduleUpdated', (e) => {
        window.dashboardVue.updateActionSequenceSchedule(e);
    })
    .listen('ActionSequenceScheduleDeleted', (e) => {
        window.dashboardVue.deleteActionSequenceSchedule(e);
    });

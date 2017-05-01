@extends('master')

@section('content')
    <script src="/js/vendors/vis/vis.js"></script>
    <link href="/css/vendors/vis/vis-network.min.css" rel="stylesheet" type="text/css" />

    <div id="ciliatus_map" style="width: 100%; height: 100%;"></div>

    <script type="text/javascript">
        $(function() {
            // create an array with nodes
            var nodes = new vis.DataSet([
                @foreach ($controlunits as $i=>$cu)
                {id: '{{ $cu->id }}', label: '{{ $cu->name }}', url: '/controlunits/{{ $cu->id }}', level: 1, group: 'Controlunits', shape: 'icon', icon: {face: 'Material Icons', code: 'developer_board', color: '#009688'}},
                @endforeach

                @foreach ($pumps as $p)
                {id: '{{ $p->id }}', label: '{{ $p->name }}', url: '/pumps/{{ $p->id }}', level: 2, group: 'Pumps', shape: 'icon', icon: {face: 'Material Icons', code: 'rotate_right', color: '#f57c00'}},
                @endforeach
                @foreach ($generic_components as $gc)
                {id: '{{ $gc->id }}', label: '{{ $gc->name }}', url: '/generic_components/{{ $gc->id }}', level: 2, group: 'GenericComponents', shape: 'icon', icon: {face: 'Material Icons', code: '{{ $gc->icon() }}', color: '#ffa726'}},
                @endforeach

                @foreach ($valves as $v)
                {id: '{{ $v->id }}', label: '{{ $v->name }}', url: '/valves/{{ $v->id }}', level: 3, group: 'Valves', shape: 'icon', icon: {face: 'Material Icons', code: 'transform', color: '#e65100'}},
                @endforeach

                @foreach ($terraria as $t)
                {id: '{{ $t->id }}', label: '{{ $t->display_name }}', url: '/terraria/{{ $t->id }}', level: 4, group: 'Terraria', shape: 'circularImage', image: '{{ $t->background_image_path() }}'},
                @endforeach

                @foreach ($animals as $a)
                {id: '{{ $a->id }}', label: '{{ $a->display_name }}', url: '/animals/{{ $a->id }}', level: 5, group: 'Animals', shape: 'circularImage', image: '{{ $a->background_image_path() }}'},
                @endforeach
                @foreach ($physical_sensors as $ps)
                {id: '{{ $ps->id }}', label: '{{ $ps->name }}', url: '/physical_sensors/{{ $ps->id }}', level: 5, group: 'PhysicalSensors', shape: 'icon', icon: {face: 'Material Icons', code: 'memory', color: '#5e35b1'}},
                @endforeach

                @foreach ($logical_sensors as $ls)
                {id: '{{ $ls->id }}', label: '{{ $ls->name }}', url: '/logical_sensors/{{ $ls->id }}', level: 6, group: 'LogicalSensors', shape: 'icon', icon: {face: 'Material Icons', code: 'memory', color: '#9c27b0'}},
                @endforeach
            ]);

            // create an array with edges
            var edges = new vis.DataSet([
                @foreach ($controlunits as $cu)
                    @foreach ($cu->valves as $v)
                    {from: '{{ $cu->id }}', to: '{{ $v->id }}'},
                    @endforeach
                    @foreach ($cu->pumps as $p)
                    {from: '{{ $cu->id }}', to: '{{ $p->id }}'},
                    @endforeach
                    @foreach ($cu->generic_components as $gc)
                    {from: '{{ $cu->id }}', to: '{{ $gc->id }}'},
                    @endforeach
                    @foreach ($cu->physical_sensors as $ps)
                    {from: '{{ $cu->id }}', to: '{{ $ps->id }}'},
                    @endforeach
                @endforeach
                @foreach ($terraria as $t)
                    @foreach ($t->animals as $a)
                    {from: '{{ $t->id }}', to: '{{ $a->id }}'},
                    @endforeach
                    @foreach ($t->valves as $v)
                    {from: '{{ $t->id }}', to: '{{ $v->id }}'},
                    @endforeach
                    @foreach ($t->physical_sensors as $ps)
                    {from: '{{ $t->id }}', to: '{{ $ps->id }}'},
                    @endforeach
                    @foreach ($t->generic_components as $gc)
                    {from: '{{ $t->id }}', to: '{{ $gc->id }}'},
                    @endforeach
                @endforeach
                @foreach ($physical_sensors as $ps)
                    @foreach ($ps->logical_sensors as $ls)
                    {from: '{{ $ps->id }}', to: '{{ $ls->id }}'},
                    @endforeach
                @endforeach
                @foreach ($pumps as $p)
                    @foreach ($p->valves as $v)
                    {from: '{{ $p->id }}', to: '{{ $v->id }}'},
                    @endforeach
                @endforeach
            ]);



            // create a network
            var container = document.getElementById('ciliatus_map');
            var data = {
                nodes: nodes,
                edges: edges
            };

            var options = {
                layout: {
//                    randomSeed: 996135
                    improvedLayout:true,
                    hierarchical: {
                        enabled: true,
                        blockShifting: true,
                        edgeMinimization: false,
                        sortMethod: 'hubsize',
                        nodeSpacing: 150
                    }
                },
                edges: {
                    smooth: {
                        type: 'straightCross',
                        forceDirection: 'horizontal',
                        roundness: 1
                    },
                    arrows: 'to',
                    arrowStrikethrough: false
                },
                interaction: {
                    dragNodes: true
                },
                physics: {
                    enabled: false,
                },
                @if(\Auth::user()->setting('permanent_nightmode_enabled') == 'on' || (\Auth::user()->setting('auto_nightmode_enabled') == 'on' && \Auth::user()->night()))
                nodes: {
                    font: {
                        color: 'white'
                    }
                },
                @else
                nodes: {
                    font: {
                        color: 'black'
                    }
                },
                @endif
                groups: {
                    Controlunits: {
                        shape: 'triangle',
                        color: '#009688' // teal
                    },
                    Animals: {
                        shape: 'diamond',
                        color: "#4caf50 " // green
                    },
                    Valves: {
                        shape: 'dot',
                        color: "#e65100  " // orange darken-4
                    },
                    Pumps: {
                        shape: 'dot',
                        color: "#f57c00 " // orange darken-2
                    },
                    GenericComponents: {
                        shape: 'dot',
                        color: "#ffa726  " // orange lighten-1
                    },
                    Terraria: {
                        shape: 'square',
                        color: "#4caf50    " // green
                    },
                    PhysicalSensors: {
                        shape: 'triangleDown',
                        color: "#5e35b1  " // purple darken-1
                    },
                    LogicalSensors: {
                        shape: 'triangleDown',
                        color: "#9c27b0 " // purple
                    }
                }
            };

            var network = new vis.Network(container, data, options);

            network.on("doubleClick", function (params) {
                if (params.nodes.length === 1) {
                    var node = nodes.get(params.nodes[0]);
                    window.open(node.url, '_blank');
                }
            });

            console.log(network.getSeed());
        });
    </script>
@stop
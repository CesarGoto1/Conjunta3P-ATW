const createEstudiantePanel = () => {
    Ext.define('App.model.Estudiante', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'nombre', type: 'string'},
            {name: 'email', type: 'string'},
            {name: 'grado', type: 'string'},
            {name: 'institucion', type: 'string'},
            {name: 'tiempo_disponible_semanal', type: 'int'},
            {name: 'equipo_id', type: 'int'}
        ]
    });

    let estudianteStore = Ext.create('Ext.data.Store', {
        storeId: 'estudianteStore',
        model: 'App.model.Estudiante',
        proxy: {
            type: 'rest',
            url: 'api/estudiante.php',
            reader: {type: 'json', rootProperty: ''},
            writer: {type: 'json', rootProperty: ''},
            appendId: false,
        },
        autoLoad: true
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Estudiantes',
        store: estudianteStore,
        itemId: 'estudiantePanel',
        layout: 'fit',
        columns: [
            {
                text: 'ID',
                width: 50,
                sortable: false,
                hideable: false,
                dataIndex: 'id'
            },
            {
                text: 'Nombre',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'nombre'
            },
            {
                text: 'Email',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'email'
            },
            {
                text: 'Grado',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'grado'
            },
            {
                text: 'Institución',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'institucion'
            },
            {
                text: 'Horas/Semana',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'tiempo_disponible_semanal'
            },
            {
                text: 'Equipo ID',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'equipo_id'
            }
        ]
    });

    return grid;
};

window.createEstudiantePanel = createEstudiantePanel;
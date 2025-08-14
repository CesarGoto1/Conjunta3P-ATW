const createEquipoPanel = () => {
    Ext.define('App.model.Equipo', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'nombre', type: 'string'},
            {name: 'hackathon', type: 'string'}
        ]
    });

    let equipoStore = Ext.create('Ext.data.Store', {
        storeId: 'equipoStore',
        model: 'App.model.Equipo',
        proxy: {
            type: 'rest',
            url: 'api/equipo.php',
            reader: {type: 'json', rootProperty: ''},
            writer: {type: 'json', rootProperty: ''},
            appendId: false,
        },
        autoLoad: true
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Equipos',
        store: equipoStore,
        itemId: 'equipoPanel',
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
                text: 'Hackathon',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'hackathon'
            }
        ]
    });

    return grid;
};

window.createEquipoPanel = createEquipoPanel;
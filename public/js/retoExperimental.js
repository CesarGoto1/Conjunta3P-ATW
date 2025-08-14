const createRetoExperimentalPanel = () => {
    Ext.define('App.model.RetoExperimental', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'descripcion', type: 'string'},
            {name: 'enfoque_pedagogico', type: 'string'}
        ]
    });

    let retoExperimentalStore = Ext.create('Ext.data.Store', {
        storeId: 'retoExperimentalStore',
        model: 'App.model.RetoExperimental',
        proxy: {
            type: 'rest',
            url: 'api/retoExperimental.php',
            reader: {type: 'json', rootProperty: ''},
            writer: {type: 'json', rootProperty: ''},
            appendId: false,
        },
        autoLoad: true
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Retos Experimentales',
        store: retoExperimentalStore,
        itemId: 'retoExperimentalPanel',
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
                text: 'Descripción',
                flex: 2,
                sortable: false,
                hideable: false,
                dataIndex: 'descripcion'
            },
            {
                text: 'Enfoque Pedagógico',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'enfoque_pedagogico'
            }
        ]
    });

    return grid;
};

window.createRetoExperimentalPanel = createRetoExperimentalPanel;

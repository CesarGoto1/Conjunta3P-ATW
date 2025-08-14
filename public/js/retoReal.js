const createRetoRealPanel = () => {
    Ext.define('App.model.RetoReal', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'descripcion', type: 'string'},
            {name: 'entidad_colaboradora', type: 'string'}
        ]
    });

    let retoRealStore = Ext.create('Ext.data.Store', {
        storeId: 'retoRealStore',
        model: 'App.model.RetoReal',
        proxy: {
            type: 'rest',
            url: 'api/retoReal.php',
            reader: {type: 'json', rootProperty: ''},
            writer: {type: 'json', rootProperty: ''},
            appendId: false,
        },
        autoLoad: true
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Retos Reales',
        store: retoRealStore,
        itemId: 'retoRealPanel',
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
                text: 'Entidad Colaboradora',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'entidad_colaboradora'
            }
        ]
    });

    return grid;
};

window.createRetoRealPanel = createRetoRealPanel;

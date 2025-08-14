const createEquipoRetoPanel = () => {
    Ext.define('App.model.EquipoReto', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'equipo_id', type: 'int'},
            {name: 'reto_id', type: 'int'}
        ]
    });

    // Store para equipos
    const equipoNombreStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'nombre'],
        proxy: {
            type: 'rest',
            url: 'api/equipo.php',
            reader: {type: 'json', rootProperty: ''}
        },
        autoLoad: true
    });

    // Store para retos reales
    const retoRealStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'descripcion'],
        proxy: {
            type: 'rest',
            url: 'api/retoReal.php',
            reader: {type: 'json', rootProperty: ''}
        },
        autoLoad: true
    });

    // Store para retos experimentales
    const retoExperimentalStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'descripcion'],
        proxy: {
            type: 'rest',
            url: 'api/retoExperimental.php',
            reader: {type: 'json', rootProperty: ''}
        },
        autoLoad: true
    });

    // Función para obtener el nombre del equipo por id
    function getEquipoNombreById(id) {
        const rec = equipoNombreStore.findRecord('id', id, 0, false, true, true);
        return rec ? rec.get('nombre') : id;
    }

    // Función para obtener la descripción del reto por id (busca en ambos stores)
    function getRetoDescripcionById(id) {
        let rec = retoRealStore.findRecord('id', id, 0, false, true, true);
        if (rec) return rec.get('descripcion');
        rec = retoExperimentalStore.findRecord('id', id, 0, false, true, true);
        if (rec) return rec.get('descripcion');
        return id;
    }

    let equipoRetoStore = Ext.create('Ext.data.Store', {
        storeId: 'equipoRetoStore',
        model: 'App.model.EquipoReto',
        proxy: {
            type: 'rest',
            url: 'api/equipoReto.php',
            reader: {type: 'json', rootProperty: ''},
            writer: {type: 'json', rootProperty: ''},
            appendId: false,
        },
        autoLoad: true
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Asignaciones Equipo-Reto',
        store: equipoRetoStore,
        itemId: 'equipoRetoPanel',
        layout: 'fit',
        columns: [
            {
                text: 'Equipo',
                width: 150,
                sortable: false,
                hideable: false,
                dataIndex: 'equipo_id',
                renderer: getEquipoNombreById
            },
            {
                text: 'Reto',
                flex:2,
                sortable: false,
                hideable: false,
                dataIndex: 'reto_id',
                renderer: getRetoDescripcionById
            }
        ]
    });

    return grid;
};

window.createEquipoRetoPanel = createEquipoRetoPanel;
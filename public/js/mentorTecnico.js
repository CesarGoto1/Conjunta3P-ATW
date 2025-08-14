const createMentorTecnicoPanel = () => {
    Ext.define('App.model.MentorTecnico', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'nombre', type: 'string'},
            {name: 'email', type: 'string'},
            {name: 'especialidad', type: 'string'},
            {name: 'experiencia', type: 'int'},
            {name: 'disponibilidad_horaria', type: 'int'},
            {name: 'equipo_id', type: 'int'}
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

    // Función para obtener el nombre del equipo por id
    function getEquipoNombreById(id) {
        const rec = equipoNombreStore.findRecord('id', id, 0, false, true, true);
        return rec ? rec.get('nombre') : '';
    }

    let mentorTecnicoStore = Ext.create('Ext.data.Store', {
        storeId: 'mentorTecnicoStore',
        model: 'App.model.MentorTecnico',
        proxy: {
            type: 'rest',
            url: 'api/mentorTecnico.php',
            reader: {type: 'json', rootProperty: ''},
            writer: {type: 'json', rootProperty: ''},
            appendId: false,
        },
        autoLoad: true
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Mentores Técnicos',
        store: mentorTecnicoStore,
        itemId: 'mentorTecnicoPanel',
        layout: 'fit',
        columns: [
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
                text: 'Especialidad',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'especialidad'
            },
            {
                text: 'Experiencia',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'experiencia'
            },
            {
                text: 'Disponibilidad Horaria',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'disponibilidad_horaria'
            },
            {
                text: 'Equipo',
                flex: 1,
                sortable: false,
                hideable: false,
                dataIndex: 'equipo_id',
                renderer: getEquipoNombreById
            }
        ]
    });

    return grid;
};
window.createMentorTecnicoPanel = createMentorTecnicoPanel;
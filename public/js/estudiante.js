const createEstudiantePanel = () =>{
    Ext.define('App.model.Estudiante', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'nombre', type: 'string'},
            {name: 'apellido', type: 'string'},
            {name: 'email', type: 'string'}
        ]
    });
}
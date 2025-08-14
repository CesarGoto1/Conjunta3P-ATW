Ext.onReady(() => {
    const retoRealPanel = createRetoRealPanel();
    const retoExperimentalPanel = createRetoExperimentalPanel();
    const estudiantePanel = createEstudiantePanel();
    const mentorTecnicoPanel = createMentorTecnicoPanel();
    const equipoPanel = createEquipoPanel();
    
    const mainCard = Ext.create('Ext.panel.Panel',{
       region: 'center',
       layout: 'card',
       items: [retoRealPanel, retoExperimentalPanel, estudiantePanel,mentorTecnicoPanel, equipoPanel]
   });

   Ext.create('Ext.container.Viewport',{
       id:'mainViewport',
       layout: 'border',
       items: [{
            region: 'north',
            xtype: 'toolbar',
                items:[
                    {
                        text: 'Retos Reales',
                        handler: ()=>mainCard.getLayout().setActiveItem(retoRealPanel)
                    },
                    {
                        text: 'Retos Experimentales',
                        handler: ()=>mainCard.getLayout().setActiveItem(retoExperimentalPanel)
                    },
                    {
                        text: 'Estudiante',
                        handler: ()=>mainCard.getLayout().setActiveItem(estudiantePanel)
                    },
                    {
                        text: 'Mentor Tecnico',
                        handler: ()=>mainCard.getLayout().setActiveItem(mentorTecnicoPanel)
                    },
                    {
                        text: 'Equipo',
                        handler: ()=>mainCard.getLayout().setActiveItem(equipoPanel)
                    },
                ]

        }, mainCard]
   });
});
Ext.onReady(() => {
    const retoRealPanel = createRetoRealPanel();
    const retoExperimentalPanel = createRetoExperimentalPanel();
    
    const mainCard = Ext.create('Ext.panel.Panel',{
       region: 'center',
       layout: 'card',
       items: [retoRealPanel, retoExperimentalPanel]
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
                    }
                ]

        }, mainCard]
   });
});
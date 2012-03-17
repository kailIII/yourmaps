<?php
/*
 * www.mapmatters.org/server/96
a
http://www.mapmatters.org/server/3660


<dl class="icons">
     <dt class="info">Capabilities</dt>
     <dd class="info"><a href="http://mapas.topografia.upm.es/cgi-bin/santu/santuarios?SERVICE=WMS&amp;REQUEST=GetCapabilities">Get Capabilities</a></dd>
     <dt class="url">URL</dt>
       <dd class="url"><span style="word-wrap:break-word">http<span style="float:left;"></span>:<span style="float:left;"></span>/<span style="float:left;"></span>/mapas<span style="float:left;"></span>.topografia<span style="float:left;"></span>.upm<span style="float:left;"></span>.es<span style="float:left;"></span>/cgi<span style="float:left;"></span>-bin<span style="float:left;"></span>/santu<span style="float:left;"></span>/santuarios</span></dd>
</dl>


Con YQL, tenemos esta query:

select * from html where url="http://www.mapmatters.org/server/3660" and
      xpath='//dd/a/@href'
      
      
que devuelve:

       <results>
        <a href="http://spatial.dcenr.gov.ie/wmsconnector/com.esri.wms.Esrimap/GSI_Simple?SERVICE=WMS&amp;REQUEST=GetCapabilities">Get Capabilities</a>
    </results>




*/

<?php
	header('Content-type: application/vnd.google-earth.kml+xml');
?>
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.2">
  <Folder xmlns="">
    <name>IDEAndalucia MTA400v</name>
    <visibility/>
    <description>Nodo del Instituto de Cartografía de Andalucía. Junta de Andalucía. Servicio WMS del Mapa Topográfico de Andalucía 1:400000 vectorial actualizado (Año 2008). Integrado en la Infraestructura de Datos Espaciales de Andalucía siguiendo las directrices del Sistema Cartográfico de Andalucía.</description>
    
    
    <LookAt>
      <longitude>-4.59981</longitude>
      <latitude>37.3479</latitude>
      <altitude>0</altitude>
      <range>1000000</range>
      <tilt>0</tilt>
      <heading>0</heading>
    </LookAt>
    
    
    <Style>
      <ListStyle>
        <listItemType>check</listItemType>
        <bgColor>00ffffff</bgColor>
        <maxSnippetLines>2</maxSnippetLines>
      </ListStyle>
    </Style>
    
    <GroundOverlay>
      <name>Usos del suelo</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>imagen raster que muestra los principales usos del suelo en Andalucía</description>
      
      <LookAt>
        <longitude>-4.574225</longitude>
        <latitude>37.3062</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      
      <drawOrder>2</drawOrder>
      
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=fondoraster&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      
      <LatLonBox>
        <north>38.8223</north>
        <south>35.7901</south>
        <east>-1.55888</east>
        <west>-7.58957</west>
      </LatLonBox>
    </GroundOverlay>
   
   
    <GroundOverlay>
      <name>Relieve</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Relieve</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Relieve&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    
    
    <GroundOverlay>
      <name>Cotas Altimétricas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Cotas Altimétricas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=cotas_altimetricas_01&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Batimetría</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Batimetría</description>
      <LookAt>
        <longitude>-4.60699</longitude>
        <latitude>36.53425</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=batimetria&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>37.418</north>
        <south>35.6505</south>
        <east>-1.53528</east>
        <west>-7.6787</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Cotas Altimétricas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Cotas Altimétricas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=cotas_altimetricas_02&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Cartografia de Contexto</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Contexto</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Contexto&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Poblamiento Exterior</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Poblamiento Exterior</description>
      <LookAt>
        <longitude>-4.605745</longitude>
        <latitude>37.2678</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=poblamiento_exterior&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.8562</north>
        <south>35.6794</south>
        <east>-1.48633</east>
        <west>-7.72516</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Láminas de Agua Exterior</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Láminas de Agua Exterior</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Laminas_agua_exterior&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Red Hídrica Exterior</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Red Hídrica Exterior</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Red_hidrica_exterior&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Red Eléctrica y de Ferrocarriles Exterior</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Red Eléctrica y de Ferrocarriles Exterior</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Red_electrica_ferrocarriles_exterior&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Nucleos de Población Exterior</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Nucleos de Población Exterior</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Nucleos_poblacion_exterior&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Poblamiento</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>poblamiento</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=poblamiento&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Poblamiento</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Poblamiento</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Poblamiento&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Hitos de Interés Cultural</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Espacios Naturales Protegidos</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=H_culturales&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Divisiones_Administrativas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Divisiones_Administrativas</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Divisiones_Administrativas&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Espacios Naturales Protegidos</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Espacios Naturales Protegidos</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=E_Naturales&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>División Administrativa</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>División Administrativa</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Division_Administrativa&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Hidrografía</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Hidrografia</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Hidrografia&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Láminas de Agua</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Láminas de Agua</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=laminas_agua&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Red Hidrográfica</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Red Hidrográfica</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Red_Hidrografica&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Infraestructura Energetica</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Infraestructura_Energetica</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Infraestructura_Energetica&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Infraestructuras Energéticas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Infraestructuras Energéticas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Infraestructuras_Energeticas&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Redes Energéticas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Redes Energéticas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Redes_energeticas&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Infraestructuras Transportes</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Infraestructuras_Transportes</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Infraestructuras_Transportes&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Puertos y Aeropuertos</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Puertos y Aeropuertos</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Puertos_aeropuertos&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Red Viaria</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Red Viaria</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Red_viaria&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Red Férrea, Marítima y de Telecomunicaciones</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Red Férrea, Marítima y de Telecomunicaciones</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Red_ferrea_maritima&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Infraestructuras de Transportes</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Infraestructuras de Transportes</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Inf_transportes&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Toponimia</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Toponimia</description>
      <LookAt>
        <longitude>NaN</longitude>
        <latitude>NaN</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Toponimia&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north/>
        <south/>
        <east/>
        <west/>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de poblamiento exterior</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de poblamiento exterior</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Toponimos_poblamiento_exterior&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de infraestructuras</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de infraestructuras</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos_infraestructuras&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos del litoral</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos del litoral</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos_litoral&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de minas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de minas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos_minas&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Toponimia de sierras</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Toponimia de sierras</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimia_sierras&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de comarcas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de comarcas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos_comarcas&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de cotas</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de cotas</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos_cotas&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de ríos</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de ríos</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Toponimos_rios&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de láminas de agua</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de láminas de agua</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos__lamina_agua&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de Espacios Naturales Protegidos</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de Espacios Naturales Protegidos</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=Toponimos_E_N&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
    <GroundOverlay>
      <name>Topónimos de Poblamiento</name>
      <visibility>0</visibility>
      <snippet/>
      <Snippet maxLines="0"/>
      <description>Topónimos de Poblamiento</description>
      <LookAt>
        <longitude>-4.59981</longitude>
        <latitude>37.3479</latitude>
        <altitude>0</altitude>
        <range>1000000</range>
        <tilt>0</tilt>
        <heading>0</heading>
      </LookAt>
      <drawOrder>2</drawOrder>
      <Icon>
        <href>http://www.ideandalucia.es/wms/mta400v_2008?service=wms&amp;VERSION=1.1.1&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=toponimos_poblamiento&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit</href>
        <viewRefreshMode>onStop</viewRefreshMode>
      </Icon>
      <LatLonBox>
        <north>38.748</north>
        <south>35.9478</south>
        <east>-1.60032</east>
        <west>-7.5993</west>
      </LatLonBox>
    </GroundOverlay>
  </Folder>
</kml>
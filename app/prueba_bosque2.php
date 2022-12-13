<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prueba_bosque2 extends Model
{
    <?php
class Reporte_model extends CI_Model 
{

        public function __construct()
        {
                $this->load->database();
                $this->load->library('session');
        }
        public function fechaHumana($valor)
        {
			
//			date ("n",)
		}
        
        /*Perfiles Abajo*/
        public function get_reporteAdopcion()
		{
			//			$query = $this->db->get('catconf_001_perfiles');					 
			//			die(print_r($query->result_array()));
		}		
		public function get_estadosLiberado()
		{			
			$sql="SELECT * FROM catconf_004_estados ";
			$query = $this->db->query($sql)->result_array();	
			return $query;			
		}	
		public function get_ciudadesLiberadas($ID__ESTADO)
		{
			$sql="SELECT * FROM catconf_005_municipios  where ID__ESTADO=? order by VCH_NOMBRE asc ";										  									  
			$query = $this->db->query($sql,array($ID__ESTADO))->result_array();	
			return $query;	
		}		
		public function getGeocerca($ID,$tipo)	//1 es muni, 0 estado
		{
			if($tipo==1)
			{
				$sql="SELECT * FROM catconf_005_municipios  where ID__MUNICIPIO=?";										  									  
				$query = $this->db->query($sql,array($ID))->row()->GEOCERCA;								
			}
			else
			{
				$sql="SELECT * FROM catconf_004_estados  where ID__ESTADO=?";										  									  
				$query = $this->db->query($sql,array($ID))->row()->GEOCERCA;		
			}			
			return $query;	
		}		
		
		
		
		public function get_selectEspecies()
		{
			$sql="SELECT ID__ESPECIE,VCH_NOMBRECOMUN FROM catconf_009_especies order by VCH_NOMBRECOMUN asc";										  									  
			$query = $this->db->query($sql)->result_array();	
			return $query;	
		}		
		/*CatalogoEventos abajo*/
		public function get_selectEventos()
		{
			$sql="SELECT ID__EVENTO,concat(substr(FEC_FECHAINICIO,1,10),\" - \",VCH_NOMBREEVENTO) as VCH_NOMBREEVENTO
						FROM catconf_065_eventos where VCH_TIPO=1  order by VCH_NOMBREEVENTO asc";										  									  
			$query = $this->db->query($sql)->result_array();	
			return $query;	
		}
		/*CatalogoEventos abajo*/
		public function get_selectEmpresas()
		{
			$sql="SELECT ID__EMPRESA,VCH_NOMBREEMPRESA
						FROM catconf_013_empresa order by VCH_NOMBREEMPRESA asc";										  									  
			$query = $this->db->query($sql)->result_array();	
			return $query;	
		}
		
		public function generaReporteAdopcion( $fechaInicio,$fechafin,$ID__EVENTO,$ID__EMPRESA,$VCH_ESTATUS  )
		{
	
		}
		
		public function getAllInfoEventos($fechaInicio,$fechafin,$ID__EVENTO,$ID__EMPRESA,$VCH_ESTATUS)
		{

			$where="where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 23:59:59' and '".$fechafin."  23:59:59' >= FEC_FECHAFIN";		
			if($ID__EVENTO!=-1)			
			{
				$where.=" and catconf_065_eventos.ID__EVENTO=".intval($ID__EVENTO);
				$nombreevento = $this->db->query("select VCH_NOMBREEVENTO from catconf_065_eventos where ID__EVENTO=?",array($ID__EVENTO))->result_array()[0]["VCH_NOMBREEVENTO"];	
			}
			else
			{
				$nombreevento ="Todos";
			}
			if($ID__EMPRESA!=-1)			
			{
				$where.=" and catconf_065_eventos.ID__EMPRESA=".intval($ID__EMPRESA);
				$nombreempresa = $this->db->query("select VCH_NOMBREEMPRESA from catconf_013_empresa where ID__EMPRESA=?",array($ID__EMPRESA))->result_array()[0]["VCH_NOMBREEMPRESA"];	
			}
			else
			{
				$nombreempresa="Todos";
			}		
			if($VCH_ESTATUS!=-1)			
			{
				$where.=" and catconf_065_eventos.VCH_ESTATUS=".intval($VCH_ESTATUS);
				switch($VCH_ESTATUS)
				{
					case 0: {$nombrestatus="Inactivo";break;}
					case 1: {$nombrestatus="Activo";break;}
					case 2:{$nombrestatus="Finalizado";break;}

				}
			}			
			else
			{
				$nombrestatus="Todos";
			}
				
			$sql="SELECT count(*) as total FROM catconf_065_eventos ".$where;	
			$sql.=" order by VCH_NOMBREEVENTO asc";										  									  
			$totaleventos = $this->db->query($sql)->result_array()[0]["total"];	
			//die($this->db->last_query());
						
			$sql="SELECT count(distinct(ID__EMPRESA)) as total FROM catconf_065_eventos ".$where;	
			$sql.=" order by VCH_NOMBREEVENTO asc";										  									  
			$totalPatrocinadores = $this->db->query($sql)->result_array()[0]["total"];				
									
			$sql="SELECT count(*) cant,substr(FEC_FECHAINICIO,1,10) as fecha FROM catconf_065_eventos  ".$where;	
			$sql.=" group by substr(FEC_FECHAINICIO,1,10)  order by substr(FEC_FECHAINICIO,1,10) asc";										  									  
			$totalEventosPorDia = $this->db->query($sql)->result_array();				
									
			$sql="SELECT count(*) cant,VCH_NOMBREEMPRESA FROM catconf_065_eventos  
					inner join catconf_013_empresa on catconf_065_eventos.ID__EMPRESA=catconf_013_empresa.ID__EMPRESA  ".$where;	
			$sql.=" group by  catconf_013_empresa.ID__EMPRESA  order by VCH_NOMBREEMPRESA asc";										  									  
			$totalEventosPorPatrocinador = $this->db->query($sql)->result_array();				
			$indaux=0;
			foreach($totalEventosPorPatrocinador as $evpatro)
			{				
				$totalEventosPorPatrocinador[$indaux++]["porcentaje"]=intval(($evpatro["cant"]/$totaleventos)*100);
			}
			//die(print_r($totalEventosPorPatrocinador));
			
			
			$sql="SELECT count(*) cant,VCH_ESTATUS FROM catconf_065_eventos ".$where;	
			$sql.=" group by  VCH_ESTATUS";										  									  
			$eventosPorEstatus = $this->db->query($sql)->result_array();	
			
			$sql="select VCH_NOMBREEVENTO,llevados,entregados from 
			(
				(
					select * from catconf_065_eventos ".$where. "
				)eventos
			inner join
			(SELECT sum(NUM_CANTIDAD) llevados,ID__EVENTO FROM catconf_066_relarboladoasignadoaevento group by ID__EVENTO)llevado
			on eventos.ID__EVENTO=llevado.ID__EVENTO
			left join
			(select sum(NUM_CANTIDAD) entregados,ID__EVENTOADOPCION from traoper_021_registroadopcion group by  ID__EVENTOADOPCION)Entregados 
			on eventos.ID__EVENTO=Entregados.ID__EVENTOADOPCION) ";												  									  
			$eventosPorEficiencia = $this->db->query($sql)->result_array();	
			//die($this->db->last_query());
			
			$sql="SELECT VCH_NOMBREEVENTO,VCH_LATITUD,VCH_LONGITUD,FEC_FECHAINICIO,FEC_FECHAFIN FROM catconf_065_eventos ".$where;	
			$sql.="";										  									  
			$eventosUbicados = $this->db->query($sql)->result_array();
			
			
			//echo "<pre>";			die(print_r(get_defined_vars()));
			$data=array();											
			$data["nombreevento"]=$nombreevento;
			$data["nombreempresa"]=$nombreempresa;
			$data["nombrestatus"]=$nombrestatus;			
			$data["totaleventos"]=$totaleventos;
			$data["totalPatrocinadores"]=$totalPatrocinadores;
			$data["totalEventosPorDia"]=$totalEventosPorDia;
			$data["totalEventosPorPatrocinador"]=$totalEventosPorPatrocinador;
			$data["eventosPorEficiencia"]=$eventosPorEficiencia;
			$data["eventosPorEstatus"]=$eventosPorEstatus;		
			$data["eventosUbicados"]=$eventosUbicados;		
			
			return $data;
		}
		public function getAllInfoReforesta($fechaInicio,$fechafin,$ID__EVENTO,$ID__EMPRESA,$VCH_ESTATUS)
		{

			$where="where VCH_TIPO=2 and FEC_FECHAINICIO >= '".$fechaInicio." 23:59:59' and '".$fechafin."  23:59:59' >= FEC_FECHAFIN";		
			if($ID__EVENTO!=-1)			
			{
				$where.=" and catconf_065_eventos.ID__EVENTO=".intval($ID__EVENTO);
				$nombreevento = $this->db->query("select VCH_NOMBREEVENTO from catconf_065_eventos where ID__EVENTO=?",array($ID__EVENTO))->result_array()[0]["VCH_NOMBREEVENTO"];	
			}
			else
			{
				$nombreevento ="Todos";
			}
			if($ID__EMPRESA!=-1)			
			{
				$where.=" and catconf_065_eventos.ID__EMPRESA=".intval($ID__EMPRESA);
				$nombreempresa = $this->db->query("select VCH_NOMBREEMPRESA from catconf_013_empresa where ID__EMPRESA=?",array($ID__EMPRESA))->result_array()[0]["VCH_NOMBREEMPRESA"];	
			}
			else
			{
				$nombreempresa="Todos";
			}		
			
				
			$sql="SELECT count(*) as total FROM catconf_065_eventos ".$where;	
			$sql.=" order by VCH_NOMBREEVENTO asc";										  									  
			$totaleventos = $this->db->query($sql)->result_array()[0]["total"];	
//			die($this->db->last_query());
						
			$sql="SELECT count(distinct(ID__EMPRESA)) as total FROM catconf_065_eventos ".$where;	
			$sql.=" order by VCH_NOMBREEVENTO asc";										  									  
			$totalPatrocinadores = $this->db->query($sql)->result_array()[0]["total"];				
			
			
			//tomar el JSON de empresas y vincular al nombre (BD no soportaba JSON 5.4)
			$sql="select replace(replace(VCH_EMPRESASREFOR,'[',''),']','') as ID__EMPRESA,ID__EVENTO from catconf_065_eventos ".$where." and VCH_EMPRESASREFOR is not null and VCH_EMPRESASREFOR!='' ";			  
			$reforestacionesPorPatrocinador = $this->db->query($sql)->result_array();
//						die(print_r($reforestacionesPorPatrocinador));				
			//todas las empresas parseo a diccionario			
			$sql="Select ID__EMPRESA,VCH_NOMBREEMPRESA from catconf_013_empresa ";			  
			$arremp = $this->db->query($sql)->result_array();							
			$temp["ID__EMPRESA"]=array();
			$temp["VCH_NOMBREEMPRESA"]=array();
			foreach($arremp as $emp)
			{
				array_push($temp["ID__EMPRESA"],intval($emp["ID__EMPRESA"]));
				array_push($temp["VCH_NOMBREEMPRESA"],$emp["VCH_NOMBREEMPRESA"]);
			}			
			$arremp=array_combine ( $temp["ID__EMPRESA"] , $temp["VCH_NOMBREEMPRESA"] );//			echo "<pre>";die(print_r($arremp));

			//Aplana el array y cuenta repetidos
			$arrayaparicion=array();
			foreach($reforestacionesPorPatrocinador as $refpat)
			{
				$tempSeparado=explode(",",$refpat["ID__EMPRESA"]  );
				foreach($tempSeparado as $sep)
				{
					array_push($arrayaparicion , 	str_replace('"','',$sep) );
				}				
			}
			$contRep =array_count_values($arrayaparicion);				
			
			//por cada uno busca el match con el ID
			$totalReforestaPorEmpresa=array();
			foreach(array_keys($contRep) as $rep)
			{
				$obj["id"]=$rep;
				$obj["nombre"]=$arremp[$rep];
				$obj["apariciones"]=$contRep[$rep];
				array_push($totalReforestaPorEmpresa,$obj);
				$contRep[$rep]=$arremp[$rep];
			}
			
			$sql="SELECT count(*) cant,substr(FEC_FECHAINICIO,1,10) as fecha FROM catconf_065_eventos  ".$where;	
			$sql.=" group by substr(FEC_FECHAINICIO,1,10)  order by substr(FEC_FECHAINICIO,1,10) asc";										  									  
			$totalEventosPorDia = $this->db->query($sql)->result_array();				
									
			$sql="SELECT count(*) as c,VCH_TIPOREFORESTA FROM catconf_065_eventos ".$where;	
			$sql.=" group by VCH_TIPOREFORESTA";										  									  
			$totalEventosPortipotemp = $this->db->query($sql)->result_array();		
						
			$totalEventosPortipo["empresarial"]=0;
			$totalEventosPortipo["masivo"]=0;
			$totalEventosPortipo["ambas"]=0;
			$totalEventosPortipo["taller"]=0;
			foreach($totalEventosPortipotemp as $tipo)
			{
				switch($tipo["VCH_TIPOREFORESTA"])
				{
					case 1:
					{
						$totalEventosPortipo["masivo"]=$totalEventosPortipo["masivo"]+$tipo["c"];						
						break;
					}
					case 2:
					{
						$totalEventosPortipo["empresarial"]=$totalEventosPortipo["empresarial"]+$tipo["c"];
						break;
					}
					case 3:
					{
						//echo "win";
						$totalEventosPortipo["taller"]=$totalEventosPortipo["taller"]+$tipo["c"];
						break;
					}
					case 4:
					{
						$totalEventosPortipo["ambas"]=$totalEventosPortipo["ambas"]+$tipo["c"];
						
						break;
					}				
				}
			}
			
			$sql="
				SELECT sum(NUM_CANTIDAD) NUM_CANTIDAD,ID__EVENTO,rel.ID__ESPECIE,VCH_NOMBRECOMUN FROM catconf_066_relarboladoasignadoaevento rel
				left join catconf_009_especies esp on rel.ID__ESPECIE=esp.ID__ESPECIE
				where ID__EVENTO in 
				(
					SELECT ID__EVENTO FROM catconf_065_eventos ".$where."
				)
				 group by ID__ESPECIE;
				";	
			$totalReforestacionesPorEspecie = $this->db->query($sql)->result_array();		
					
			$sql="
				SELECT sum(NUM_CANTIDAD) NUM_CANTIDAD,rel.ID__EVENTO,rel.ID__ESPECIE,VCH_NOMBRECOMUN,VCH_NOMBREEVENTO,FEC_FECHAINICIO FROM catconf_066_relarboladoasignadoaevento rel
				left join catconf_009_especies esp on rel.ID__ESPECIE=esp.ID__ESPECIE
				left join catconf_065_eventos ev on rel.ID__EVENTO=ev.ID__EVENTO
				where rel.ID__EVENTO in 
				(
					SELECT ID__EVENTO FROM catconf_065_eventos ".$where."
				)
				 group by rel.ID__EVENTO, ID__ESPECIE ORDER BY rel.ID__EVENTO ASC;
				";	
			$totalReforestacionesPorEventoArbolado = $this->db->query($sql)->result_array();				
			//echo "<pre>";	die(print_r($totalReforestacionesPorEventoArbolado))	;
			
			$sql="SELECT sum(NUM_CANTIDAD) CANT,rel.ID__EVENTO,VCH_NOMBREEVENTO FROM catconf_065_eventos inner join catconf_066_relarboladoasignadoaevento rel
				on catconf_065_eventos.ID__EVENTO=rel.ID__EVENTO  ".$where." group by rel.ID__EVENTO
				";	
			$GRAFTOTALARBOLADO = $this->db->query($sql)->result_array();				
		
			$data=array();											
			$data["nombreevento"]=$nombreevento;
			$data["nombreempresa"]=$nombreempresa;		
			$data["totaleventos"]=$totaleventos;
			$data["totalPatrocinadores"]=$totalPatrocinadores;
			$data["totalEventosPorDia"]=$totalEventosPorDia;
			$data["totalEventosPortipo"]=$totalEventosPortipo;
			$data["reforestacionesPorPatrocinador"]=$totalReforestaPorEmpresa;
			$data["GRAFTOTALARBOLADO"]=$GRAFTOTALARBOLADO;
			$data["totalReforestacionesPorEspecie"]=$totalReforestacionesPorEspecie;
			$data["totalReforestacionesPorEventoArbolado"]=$totalReforestacionesPorEventoArbolado;
			
			return $data;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		public function getAllInfoAdopciudadana($fechaInicio,$fechafin,$ID__EVENTO,$ID__EMPRESA,$ID__ESPECIE,$listado)
		{
			$where="where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 00:00:00' and '".$fechafin."  23:59:59' >= FEC_FECHAFIN";		
			$whereespecie="";
			if($ID__EVENTO!=-1)			
			{
				$where.=" and catconf_065_eventos.ID__EVENTO=".intval($ID__EVENTO);
				$nombreevento = $this->db->query("select VCH_NOMBREEVENTO from catconf_065_eventos where ID__EVENTO=?",array($ID__EVENTO))->result_array()[0]["VCH_NOMBREEVENTO"];	
			}
			else
			{
				$nombreevento ="Todos";
			}
			if($ID__EMPRESA!=-1)			
			{
				$where.=" and catconf_065_eventos.ID__EMPRESA=".intval($ID__EMPRESA);
				$nombreempresa = $this->db->query("select VCH_NOMBREEMPRESA from catconf_013_empresa where ID__EMPRESA=?",array($ID__EMPRESA))->result_array()[0]["VCH_NOMBREEMPRESA"];	
			}
			else
			{
				$nombreempresa="Todos";
			}		
			if($ID__ESPECIE!=-1)			
			{
				$nombreespecie=$this->db->query("select VCH_NOMBRECOMUN from catconf_009_especies where ID__ESPECIE=?",array($ID__ESPECIE))->result_array()[0]["VCH_NOMBRECOMUN"];	
			}			
			else
			{
				$nombreespecie="Todos";
			}					
																																
			$sql="SELECT count(*) as total FROM catconf_065_eventos ".$where;				
			if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
			{
				$sql.=" and ID__EVENTO in (";
					$sql.=" 
						select ID__EVENTOADOPCION as ID__EVENTO from traoper_021_registroadopcion reg inner join catconf_020_arboles arb
						on reg.ID__ARBOL=arb.ID__ARBOL
						where ID__ESPECIE = ".$ID__ESPECIE."
						group by ID__EVENTOADOPCION";
				$sql.=")";
			}													  									  
			$totaleventos = $this->db->query($sql)->result_array()[0]["total"];	
			//die($this->db->last_query());
			
			$sql="SELECT count(distinct(ID__EMPRESA)) as total FROM catconf_065_eventos ".$where;				
			if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
			{
				$sql.=" and ID__EVENTO in (";
					$sql.=" 
						select ID__EVENTOADOPCION as ID__EVENTO from traoper_021_registroadopcion reg inner join catconf_020_arboles arb
						on reg.ID__ARBOL=arb.ID__ARBOL
						where ID__ESPECIE = ".$ID__ESPECIE."
						group by ID__EVENTOADOPCION";
				$sql.=")";
			}													  									  
			$totalpatrocinadores = $this->db->query($sql)->result_array()[0]["total"];	
			//die($this->db->last_query());
									
			
			$whereTotalEspecie="";
			if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
			{
				$whereTotalEspecie=" where ID__ESPECIE=".intval($ID__ESPECIE);
			}
			$whereTotal=" where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 00:00:00' and '".$fechafin." 23:59:59' >= FEC_FECHAFIN ";
			if($ID__EMPRESA!=-1)			
			{
				$whereTotal.=" and ID__EMPRESA=".intval($ID__EMPRESA);
			}
			if($ID__EVENTO!=-1)			
			{
				$whereTotal.=" and ID__EVENTO=".intval($ID__EVENTO);
			}						
			$sql="select count(*) total from traoper_021_registroadopcion t
							inner join
							(
								select ID__ARBOL from catconf_020_arboles ".$whereTotalEspecie."
							)arboles on t.ID__ARBOL=arboles.ID__ARBOL			
			 where 
					ID__EVENTOADOPCION in 
					(
						select ID__EVENTO from
						(SELECT * FROM catconf_065_eventos ".$whereTotal.") ev						
					)";			
			$totaladoptados = $this->db->query($sql)->row()->total;	
//			die($this->db->last_query());						
			
			$whereTotalEspecie="";
			if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
			{
				$whereTotalEspecie=" where ID__ESPECIE=".intval($ID__ESPECIE);
			}
			$whereTotal=" where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 00:00:00' and '".$fechafin." 23:59:59' >= FEC_FECHAFIN ";
			if($ID__EMPRESA!=-1)			
			{
				$whereTotal.=" and ID__EMPRESA=".intval($ID__EMPRESA);
			}
			if($ID__EVENTO!=-1)			
			{
				$whereTotal.=" and ID__EVENTO=".intval($ID__EVENTO);
			}						
			$sql="select substr(FEC_FECHA,1,10) fecha,sum( NUM_CANTIDAD) NUM_CANTIDAD from traoper_021_registroadopcion t
						inner join
						(
							select ID__ARBOL from catconf_020_arboles ".$whereTotalEspecie."
						)arboles on t.ID__ARBOL=arboles.ID__ARBOL			
					where 
					ID__EVENTOADOPCION in 
					(
						select ID__EVENTO from
						(SELECT * FROM catconf_065_eventos ".$whereTotal.") ev
					) group by substr(FEC_FECHA,1,10)";			
			$totalporfecha = $this->db->query($sql)->result_array();
			//die($sql);
			
			
			
			
			
			$whereTotalEspecie="";
			if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
			{
				$whereTotalEspecie=" where ID__ESPECIE=".intval($ID__ESPECIE);
			}
			$whereTotal=" where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 00:00:00' and '".$fechafin." 23:59:59' >= FEC_FECHAFIN ";
			if($ID__EMPRESA!=-1)			
			{
				$whereTotal.=" and ID__EMPRESA=".intval($ID__EMPRESA);
			}
			if($ID__EVENTO!=-1)			
			{
				$whereTotal.=" and ID__EVENTO=".intval($ID__EVENTO);
			}						
			$sql="select VCH_NOMBREEVENTO,count(*) NUM_CANTIDAD from traoper_021_registroadopcion t
					inner join catconf_065_eventos on t.ID__EVENTOADOPCION=catconf_065_eventos.ID__EVENTO
					inner join
						(
							select ID__ARBOL from catconf_020_arboles ".$whereTotalEspecie."
						)arboles on t.ID__ARBOL=arboles.ID__ARBOL
			 where 
					ID__EVENTOADOPCION in 
					(
						select ID__EVENTO from
						(SELECT * FROM catconf_065_eventos ".$whereTotal.") ev						
					) group by ID__EVENTOADOPCION";					  									  
			$totalporevento = $this->db->query($sql)->result_array();	
//	echo "<pre>";		die(print_r($totalporevento));
			
				
			$sql="			
			select VCH_NOMBREEMPRESA,ID__EMPRESA,sum(cant) cant from
			(
				select VCH_NOMBREEMPRESA,emp.ID__EMPRESA,ID__EVENTO from
				(select VCH_NOMBREEMPRESA,ID__EMPRESA from catconf_013_empresa) emp
				inner join
				(
					select ID__EVENTO,ID__EMPRESA from catconf_065_eventos ".$where." 
				) idempresas on emp.ID__EMPRESA=idempresas.ID__EMPRESA
			)exterior
				inner join 
				(
					 select ID__EVENTOADOPCION as ID__EVENTO,sum(reg.NUM_CANTIDAD) cant from traoper_021_registroadopcion reg 
					 inner join catconf_020_arboles arb on reg.ID__ARBOL=arb.ID__ARBOL";					 
					 if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
						{
							$sql.=" where ID__ESPECIE = ".$ID__ESPECIE;						
						}					 
					$sql.=" group by ID__EVENTOADOPCION
				)adop on exterior.ID__EVENTO=adop.ID__EVENTO
				group by ID__EMPRESA
				";					

			$totalporpatronos = $this->db->query($sql)->result_array();							
//				die($this->db->last_query());
//die(print_r($totalporpatronos));











			/*
			$sql="			
			select sum(cant) cant ,esp.ID__ESPECIE, VCH_NOMBRECOMUN from
			(
				select ID__EVENTO,ID__EMPRESA from catconf_065_eventos ".$where." 
			)exterior

			inner join 
			(
					 select ID__EVENTOADOPCION as ID__EVENTO,sum(reg.NUM_CANTIDAD) cant, ID__ESPECIE  from traoper_021_registroadopcion reg 
				 inner join catconf_020_arboles arb on reg.ID__ARBOL=arb.ID__ARBOL";					 
				 if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
					{
						$sql.=" where ID__ESPECIE = ".$ID__ESPECIE;						
					}					 
				$sql.="  group by ID__ESPECIE,ID__EVENTO
			)adop on exterior.ID__EVENTO=adop.ID__EVENTO
			inner join catconf_009_especies esp on adop.ID__ESPECIE=esp.ID__ESPECIE
			group by ID__ESPECIE
				";					
			$totalporespecie = $this->db->query($sql)->result_array();	*/
			

			$whereTotalEspecie="";
			if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
			{
				$whereTotalEspecie=" where ID__ESPECIE=".intval($ID__ESPECIE);
			}
			$whereTotal=" where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 00:00:00' and '".$fechafin." 23:59:59' >= FEC_FECHAFIN ";
			if($ID__EMPRESA!=-1)			
			{
				$whereTotal.=" and ID__EMPRESA=".intval($ID__EMPRESA);
			}
			if($ID__EVENTO!=-1)			
			{
				$whereTotal.=" and ID__EVENTO=".intval($ID__EVENTO);
			}						
			$sql="select VCH_NOMBRECOMUN,sum(NUM_CANTIDAD) as cant from traoper_021_registroadopcion t
						inner join
						(
							select ID__ARBOL,ID__ESPECIE from catconf_020_arboles ".$whereTotalEspecie."
						)arboles on t.ID__ARBOL=arboles.ID__ARBOL			
						inner join catconf_009_especies esp on arboles.ID__ESPECIE=esp.ID__ESPECIE
					where 
					ID__EVENTOADOPCION in 
					(
						select ID__EVENTO from
						(SELECT * FROM catconf_065_eventos ".$whereTotal.") ev
					) group by esp.ID__ESPECIE";			
			$totalporespecie = $this->db->query($sql)->result_array();
			//die($sql);
			
			//die($this->db->last_query());
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			$sql="			
			select VCH_LATITUD,VCH_LONGITUD,ID__EVENTOADOPCION from catconf_020_arboles arb
			inner join traoper_021_registroadopcion tra on arb.ID__ARBOL=tra.ID__ARBOL
			where ";
			 if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
					{
						$sql.=" ID__ESPECIE = ".$ID__ESPECIE." and ";						
					}	
			$sql.="		
			  ID__EVENTOADOPCION in
			(
				select ID__EVENTO from catconf_065_eventos ".$where." 
			)";					
			$localizaciones = $this->db->query($sql)->result_array();	
			//die($this->db->last_query());
				
				
			$list=null;
			if($listado==1)
			{
				$whereTotalEspecie="";
				$andwhereTotalEspecie="";
				if($ID__ESPECIE!=-1&&$ID__ESPECIE!='')
				{
					$whereTotalEspecie=" where ID__ESPECIE=".intval($ID__ESPECIE);
					$andwhereTotalEspecie=" and catconf_020_arboles.ID__ESPECIE=".intval($ID__ESPECIE);
				}
				$whereTotal=" where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 00:00:00' and '".$fechafin." 23:59:59' >= FEC_FECHAFIN ";
				if($ID__EMPRESA!=-1)			
				{
					$whereTotal.=" and ID__EMPRESA=".intval($ID__EMPRESA);
				}
				if($ID__EVENTO!=-1)			
				{
					$whereTotal.=" and ID__EVENTO=".intval($ID__EVENTO);
				}						
				$sql="select ID__EVENTOADOPCION,VCH_CODIGOQR,concat(VCH_NOMBRE,' ',VCH_APELLIDOPATERNO,' ',VCH_APELLIDOMATERNO) nombre , concat(VCH_CALLE,' ',VCH_ENTRECALLE) domicilio,VCH_NOMBRECOMUN 
					 from traoper_021_registroadopcion
						inner join catconf_065_eventos on traoper_021_registroadopcion.ID__EVENTOADOPCION=catconf_065_eventos.ID__EVENTO				
						inner join catconf_012_guardabosques on traoper_021_registroadopcion.ID__GUARDABOSQUE=catconf_012_guardabosques.ID__GUARDABOSQUE				
						inner join catconf_020_arboles on traoper_021_registroadopcion.ID__ARBOL=catconf_020_arboles.ID__ARBOL				
						inner join catconf_009_especies esp on catconf_020_arboles.ID__ESPECIE =esp.ID__ESPECIE
						left join catconf_008_domicilios dom on catconf_012_guardabosques.ID__DOMICILIO=dom.ID__DOMICILIO
				 where 
						ID__EVENTOADOPCION in 
						(
							select ID__EVENTO from
							(SELECT * FROM catconf_065_eventos ".$whereTotal.") ev
							inner join
							(
								select ID__ARBOL from catconf_020_arboles ".$whereTotalEspecie."
							)arboles
						) ".$andwhereTotalEspecie;					  									  
				$list  = $this->db->query($sql)->result_array();	
			}
//			die($this->db->last_query());
			

			
			$data["nombreevento"]=$nombreevento;
			$data["nombreempresa"]=$nombreempresa;
			$data["nombreespecie"]=$nombreespecie;
			$data["totaleventos"]=$totaleventos;
			$data["totalpatrocinadores"]=$totalpatrocinadores;		
//			$data["totaladoptados"]=$totaladoptados;	
			$data["totaladoptados"]=$totaladoptados;	
			$data["totalporfecha"]=$totalporfecha;		
			$data["totalporevento"]=$totalporevento;
			$data["totalporpatronos"]=$totalporpatronos;					
			$data["totalporespecie"]=$totalporespecie;	
			$data["localizaciones"]=$localizaciones;
			$data["listado"]=$listado;	
			$data["list"]=$list;	
							
			return $data;
		}
		public function getAllInfoSupervivencia($fechaInicio,$fechafin,$ID__EVENTO,$ID__EMPRESA,$VCH_ESTATUS,$ID__ESPECIE,$mostrarFotos,$mostrarListado)
		{			
			
			$where="where VCH_TIPO=1 and FEC_FECHAINICIO >= '".$fechaInicio." 23:59:59' and '".$fechafin."  23:59:59' >= FEC_FECHAFIN";		
			if($ID__EVENTO!=-1)			
			{				
				$nombreevento = $this->db->query("select VCH_NOMBREEVENTO from catconf_065_eventos where ID__EVENTO=?",array($ID__EVENTO))->result_array()[0]["VCH_NOMBREEVENTO"];	
				$where.=" and eventos.ID__EVENTO=".intval($ID__EVENTO);
			}
			else
			{
				$nombreevento ="Todos";
			}
			if($ID__EMPRESA!=-1)			
			{
				$nombreempresa = $this->db->query("select VCH_NOMBREEMPRESA from catconf_013_empresa where ID__EMPRESA=?",array($ID__EMPRESA))->result_array()[0]["VCH_NOMBREEMPRESA"];	
				$where.=" and eventos.ID__EMPRESA=".intval($ID__EMPRESA);
			}
			else
			{
				$nombreempresa="Todos";
			}		
			if($ID__ESPECIE!=-1)			
			{
				$nombreespecie=$this->db->query("select VCH_NOMBRECOMUN from catconf_009_especies where ID__ESPECIE=?",array($ID__ESPECIE))->result_array()[0]["VCH_NOMBRECOMUN"];	
				$where.=" and arb.ID__ESPECIE=".intval($ID__ESPECIE);
			}			
			else
			{
				$nombreespecie="Todos";
			}				
			if($VCH_ESTATUS!=0)			
			{
				switch($VCH_ESTATUS)
				{
					case 1:
					{
						$nombreestatus="Vivos y sanos";	
						$where.=" and segui.VCH_ESTADO='V' and segui.VCH_SALUD='S' ";
						break;
					}
					case 2:
					{
						$nombreestatus="Vivos y enfermos";	
						$where.=" and segui.VCH_ESTADO='V' and segui.VCH_SALUD='E' ";
						break;
					}
					case 3:
					{
						$nombreestatus="Muertos";	
						$where.=" and segui.VCH_ESTADO='M'";
						break;
					}
				}								
			}			
			else
			{
				$nombreestatus="Todos";
			}				
			
			if($mostrarFotos){$mf="Si";} else{$mf="No";}
			if($mostrarListado){$ml="Si";} else{$ml="No";}

				
				
																	
						
			$sql="select * from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION
				".$where;
			$rowsFiltrados = $this->db->query($sql)->result_array();		

						
			$sqlpatrocinadores="select  count(distinct(ID__EMPRESA)) total from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION
				".$where;
			$TotalPatrocinadores = $this->db->query($sqlpatrocinadores)->row()->total;								
			
			$sqlsupervivencia="select VCH_ESTADO,VCH_SALUD,count(*) cant from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION ".$where;
                $sqlsupervivencia.=" group by VCH_ESTADO,VCH_SALUD";
			$supervivencia = $this->db->query($sqlsupervivencia)->result_array();								
			$supervivenciaProcesada["vivosysanos"]=0;
			$supervivenciaProcesada["vivosyenfermos"]=0;
			$supervivenciaProcesada["muertos"]=0;
			foreach($supervivencia as $sup)
			{
				if($sup["VCH_ESTADO"]=="V" and  $sup["VCH_SALUD"]=="S")
				{
					$supervivenciaProcesada["vivosysanos"]=$sup["cant"];

				}
				if($sup["VCH_ESTADO"]=="V" and  $sup["VCH_SALUD"]=="E")
				{
					$supervivenciaProcesada["vivosyenfermos"]=$sup["cant"];		
				}
				if($sup["VCH_ESTADO"]=="M")
				{
					$supervivenciaProcesada["muertos"]+=$sup["cant"];
				}
			}

									
			$sqlcondiciones="select VCH_CONTENEDOR,VCH_ACCESO_AL_ARBOL,VCH_UBICACION_REPORTADA,count(*) cant from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION ".$where;
                $sqlcondiciones.=" group by VCH_CONTENEDOR,VCH_ACCESO_AL_ARBOL,VCH_UBICACION_REPORTADA";
			$condiciones = $this->db->query($sqlcondiciones)->result_array();
			$condicionesProcesada["bolsa"]=0;
			$condicionesProcesada["PlantadoPrivadoLocalizado"]=0;$condicionesProcesada["PlantadoPrivadoTransportado"]=0;
			$condicionesProcesada["PlantadoPublicoLocalizado"]=0;$condicionesProcesada["PlantadoPublicoTransportado"]=0;
			foreach($condiciones as $cond)
			{
				if($cond["VCH_CONTENEDOR"]=="B")
				{
					$condicionesProcesada["bolsa"]+=$cond["cant"];
				}															
				if($cond["VCH_CONTENEDOR"]=="P" and  $cond["VCH_ACCESO_AL_ARBOL"]=="R" and  $cond["VCH_UBICACION_REPORTADA"]=="O")
				{
					$condicionesProcesada["PlantadoPrivadoLocalizado"]+=$cond["cant"];
				}				
				if($cond["VCH_CONTENEDOR"]=="P" and  $cond["VCH_ACCESO_AL_ARBOL"]=="R" and  $cond["VCH_UBICACION_REPORTADA"]=="T")
				{
					$condicionesProcesada["PlantadoPrivadoTransportado"]+=$cond["cant"];
				}				
				if($cond["VCH_CONTENEDOR"]=="P" and  $cond["VCH_ACCESO_AL_ARBOL"]=="P" and  $cond["VCH_UBICACION_REPORTADA"]=="O")
				{
					$condicionesProcesada["PlantadoPublicoLocalizado"]+=$cond["cant"];
				}				
				if($cond["VCH_CONTENEDOR"]=="P" and  $cond["VCH_ACCESO_AL_ARBOL"]=="P" and  $cond["VCH_UBICACION_REPORTADA"]=="T")
				{
					$condicionesProcesada["PlantadoPublicoTransportado"]+=$cond["cant"];
				}				
			}	


			$sqlConEtiqueta="select VCH_CON_ETIQUETA,count(*) cant from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION ".$where;
                $sqlConEtiqueta.=" group by VCH_CON_ETIQUETA";
			$ConEtiquetarows = $this->db->query($sqlConEtiqueta)->result_array();
			$ConEtiqueta["si"]=0;$ConEtiqueta["no"]=0;
			foreach($ConEtiquetarows as $ConEti)
			{
				if($ConEti["VCH_CON_ETIQUETA"]=="S")
				{
					$ConEtiqueta["si"]+=$ConEti["cant"];
				}
				if($ConEti["VCH_CON_ETIQUETA"]=="N")
				{
					$ConEtiqueta["no"]+=$ConEti["cant"];
				}
			}

			$sqlSupervivenciaEspecie="select arb.ID__ESPECIE,VCH_ESTADO,VCH_NOMBRECOMUN, count(*) cant from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL
  			    left join catconf_009_especies especie on especie.ID__ESPECIE=arb.ID__ESPECIE           
				
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION ".$where;
            $sqlSupervivenciaEspecie.=" group by arb.ID__ESPECIE,segui.VCH_ESTADO";
			$SupervivenciaEspecieRows = $this->db->query($sqlSupervivenciaEspecie)->result_array();			
//			die($this->db->last_query());
			$SupervivenciaEspecieProcesado=array_flip(array_unique (array_column($SupervivenciaEspecieRows, 'ID__ESPECIE')));			

			
//			die(print_r($SupervivenciaEspecieRows));
			foreach($SupervivenciaEspecieRows as $supervr)
			{	
				if(!is_array($SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]))
				{
					$SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]=array();
					$SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]["M"]=0;
					$SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]["V"]=0;
				}								
								
				$SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]["NOMBRE"]=$supervr["VCH_NOMBRECOMUN"];
				
				if($supervr["VCH_ESTADO"]=="M")		
				{
					$SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]["M"]+=$supervr["cant"]					;					
				}
				if($supervr["VCH_ESTADO"]=="V")		
				{
					$SupervivenciaEspecieProcesado[$supervr["ID__ESPECIE"]]["V"]+=$supervr["cant"]					;					
				}					
			}
			
			
			$sqlSupervivenciaPatrocinador="select eventos.ID__EMPRESA,VCH_ESTADO,VCH_NOMBREEMPRESA, count(*) cant from
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
				left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL      
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION 
				left join catconf_013_empresa emp  on emp.ID__EMPRESA=eventos.ID__EMPRESA        				
				".$where;

            $sqlSupervivenciaPatrocinador.=" group by eventos.ID__EMPRESA,segui.VCH_ESTADO";
			$SupervivenciaPatrocinadorRows = $this->db->query($sqlSupervivenciaPatrocinador)->result_array();			
			$SupervivenciaPatrocinadorProcesado=array_flip(array_unique (array_column($SupervivenciaPatrocinadorRows, 'ID__EMPRESA')));			
			
			foreach($SupervivenciaPatrocinadorRows as $supervr)
			{	
				if(!is_array($SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]))
				{
					$SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]=array();
					$SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]["M"]=0;
					$SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]["V"]=0;
					$SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]["NOMBRE"]=$supervr["VCH_NOMBREEMPRESA"];
				}
				
				
				
				if($supervr["VCH_ESTADO"]=="M")		
				{
					$SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]["M"]+=$supervr["cant"]					;					
				}
				if($supervr["VCH_ESTADO"]=="V")		
				{
					$SupervivenciaPatrocinadorProcesado[$supervr["ID__EMPRESA"]]["V"]+=$supervr["cant"]					;					
				}					
			}
//			DIE(print_r($SupervivenciaPatrocinadorProcesado));
			
			$mldataQuery=" select arb.VCH_CODIGOQR as codigo ,VCH_NOMBRECOMUN as especie, concat(VCH_NOMBRE,' ',VCH_APELLIDOPATERNO,' ',VCH_APELLIDOMATERNO)as guardabosques,segui.VCH_ESTADO as estado,segui.VCH_SALUD as salud
			
				from
					(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
					left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
					left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL      
					left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION 
					left join catconf_012_guardabosques gua  on gua.ID__GUARDABOSQUE=adop.ID__GUARDABOSQUE
					left join catconf_009_especies esp  on esp.ID__ESPECIE=arb.ID__ESPECIE                                                        				
				".$where;
			$mldata = $this->db->query($mldataQuery)->result_array();			
			
			
			$mfdataQuery=" select arb.VCH_CODIGOQR as codigo ,VCH_NOMBRECOMUN as especie, concat(VCH_NOMBRE,' ',VCH_APELLIDOPATERNO,' ',VCH_APELLIDOMATERNO)as guardabosques,segui.VCH_ESTADO as estado,segui.VCH_SALUD as salud
			,VCH_RUTA_FOTO_COMPLETA,VCH_RUTA_FOTO_SOLOETIQUETA
				from
					(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
					left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL
					left join traoper_021_registroadopcion adop on adop.ID__ARBOL= segui.ID__ARBOL      
					left join catconf_065_eventos eventos on eventos.ID__EVENTO=adop.ID__EVENTOADOPCION 
					left join catconf_012_guardabosques gua  on gua.ID__GUARDABOSQUE=adop.ID__GUARDABOSQUE
					left join catconf_009_especies esp  on esp.ID__ESPECIE=arb.ID__ESPECIE                                                        				
				".$where;
			$mfdata = $this->db->query($mfdataQuery)->result_array();			
			
		
//			ECHO $SupervivenciaEspecieProcesado[466]["NOMBRE"];
//			ECHO "<PRE>";		die(print_r($SupervivenciaEspecieProcesado));
						
			$data["nombreevento"]=$nombreevento; //
			$data["nombreempresa"]=$nombreempresa;//
			$data["nombreespecie"]=$nombreespecie;//
			$data["nombreestatus"]=$nombreestatus;//			
			
			$data["mf"]=$mf;//
			$data["mfdata"]=$mfdata;
			
			$data["ml"]=$ml;//			
			$data["mldata"]=$mldata;
			
			$data["supervivenciaConsulta"]=count($rowsFiltrados);							
			$data["TotalPatrocinadores"]=count($TotalPatrocinadores);			
			$data["supervivencia"]=$supervivenciaProcesada;									
			$data["condiciones"]=$condicionesProcesada;					
			$data["ConEtiqueta"]=$ConEtiqueta;			
			$data["SupervivenciaEspecie"]=$SupervivenciaEspecieProcesado;
			$data["SupervivenciaPatrocinador"]=$SupervivenciaPatrocinadorProcesado;
			
			return $data;
		}
		public function getAllInfoSupervivenciaReforestal($fechaInicio,$fechafin,$ID__EVENTO,$ID__EMPRESA,$ID__ESPECIE,$mostrarFotos,$mostrarListado)
		{			
			
			$where="where VCH_TIPO=2 and FEC_FECHAINICIO >= '".$fechaInicio." 23:59:59' and '".$fechafin."  23:59:59' >= FEC_FECHAFIN";		
			if($ID__EVENTO!=-1)			
			{				
				$nombreevento = $this->db->query("select VCH_NOMBREEVENTO from catconf_065_eventos where ID__EVENTO=?",array($ID__EVENTO))->result_array()[0]["VCH_NOMBREEVENTO"];	
				$where.=" and eventos.ID__EVENTO=".intval($ID__EVENTO);	
			}
			else
			{
				$nombreevento ="Todos";
			}
			if($ID__EMPRESA!=-1)			
			{
				$nombreempresa = $this->db->query("select VCH_NOMBREEMPRESA from catconf_013_empresa where ID__EMPRESA=?",array($ID__EMPRESA))->result_array()[0]["VCH_NOMBREEMPRESA"];	
				$where.=" and VCH_EMPRESASREFOR like'%\"".intval($ID__EMPRESA)."\"%'";				
			}
			else
			{
				$nombreempresa="Todos";
			}		
			
			
			$whereESPECIE="";
			if($ID__ESPECIE!=-1)			
			{
				$nombreespecie=$this->db->query("select VCH_NOMBRECOMUN from catconf_009_especies where ID__ESPECIE=?",array($ID__ESPECIE))->result_array()[0]["VCH_NOMBRECOMUN"];	
				$whereESPECIE.=" WHERE t1.ID__ESPECIE=".intval($ID__ESPECIE);
				
				$sql="select count(*) as total from catconf_065_eventos eventos left join catconf_066_relarboladoasignadoaevento rel on eventos.ID__EVENTO=rel.ID__EVENTO ".$where;
				$cantidadEventos=$rowsFiltrados = $this->db->query($sql)->row()->total;	
				
			}			
			else
			{
				$sql="select count(*) as total from catconf_065_eventos eventos ".$where;
				$cantidadEventos=$rowsFiltrados = $this->db->query($sql)->row()->total;	
				
				$nombreespecie="Todos";
			}										
									
			
			//die($this->db->last_query());
			

			
			$sqlsupervivencia="select sum(NUM_SANOS) NUM_SANOS,sum(NUM_ENFERMOS) NUM_ENFERMOS,sum(NUM_VIVOS) NUM_VIVOS,sum(NUM_MUERTOS) NUM_MUERTOS  from
			(SELECT * FROM traoper_055_supervivenciareforesta t1 ".$whereESPECIE." order by ID_SUPERVIVENCIA desc)a
			left join catconf_065_eventos eventos on eventos.ID__EVENTO=a.ID__EVENTO
			". $where."
			group by a.ID__EVENTO ;
			";
			$supervivencia = $this->db->query($sqlsupervivencia,array())->result_array();								
			
			$sqlSupervivenciaEspecie="select  sum(NUM_SANOS) NUM_SANOS,sum(NUM_ENFERMOS) NUM_ENFERMOS,sum(NUM_VIVOS) NUM_VIVOS,sum(NUM_MUERTOS) NUM_MUERTOS,VCH_NOMBRECOMUN  
								from
								(
									SELECT t1.* FROM traoper_055_supervivenciareforesta t1
									 JOIN (SELECT ID__ESPECIE, MAX(ID_SUPERVIVENCIA) ID_SUPERVIVENCIA FROM traoper_055_supervivenciareforesta  GROUP BY ID__ESPECIE,ID__EVENTO ) t2
									ON t1.ID_SUPERVIVENCIA = t2.ID_SUPERVIVENCIA AND t1.ID__ESPECIE = t2.ID__ESPECIE
									".$whereESPECIE."
								)a
								left join catconf_065_eventos eventos on eventos.ID__EVENTO=a.ID__EVENTO
								LEFT JOIN catconf_009_especies esp ON a.ID__ESPECIE=esp.ID__ESPECIE
								". $where."
								group by a.ID__ESPECIE ;
			";
			$supervivenciaEspecie = $this->db->query($sqlSupervivenciaEspecie,array())->result_array();			
						
			

			///
			///Dado que la BD aun no soportaba JSON se tuvo que aterrizar ... puede ser optimizado
			//obtencion de patrocinadores implicados
			$sqlPatrocinadoresImplicados="Select VCH_EMPRESASREFOR  from
											(SELECT * FROM traoper_055_supervivenciareforesta t1 ".$whereESPECIE." order by ID_SUPERVIVENCIA desc)a
										  left join catconf_065_eventos eventos on eventos.ID__EVENTO=a.ID__EVENTO		
										  ". $where."
										  group by a.ID__EVENTO";
			$patrocinadoresImplicados = $this->db->query($sqlPatrocinadoresImplicados,array())->result_array();			
			$listaPatrocinadores=array();
			$listaPatrocinadoresUnicos=array();
			foreach($patrocinadoresImplicados as $patro)
			{
				$TEMP=JSON_DECODE($patro["VCH_EMPRESASREFOR"]);
				foreach($TEMP as $empresa)
				{
					if(!in_array($empresa,$listaPatrocinadoresUnicos))
					{						
						$sqlNombreEmpresa="Select VCH_NOMBREEMPRESA as VCH_NOMBRECOMUN  from catconf_013_empresa where ID__EMPRESA=".$empresa;
						$nombrempresa = $this->db->query($sqlNombreEmpresa)->row()->VCH_NOMBRECOMUN;							
						$empresaArmada= array();
						$empresaArmada["ID__EMPRESA"]=$empresa;
						$empresaArmada["VCH_NOMBRECOMUN"]=$nombrempresa;
						array_push($listaPatrocinadores,$empresaArmada);
						array_push($listaPatrocinadoresUnicos,$empresa);
					}
				}
			}

			$listaSupervivenciaPatrocinador=array();
			foreach($listaPatrocinadores as $patrocinador)
			{
					$sqlSupervivenciaPatrocinador="select '".$patrocinador["ID__EMPRESA"]."' as ID__EMPRESA,'".$patrocinador["VCH_NOMBRECOMUN"]."' as VCH_NOMBRECOMUN,sum(NUM_SANOS) NUM_SANOS,sum(NUM_ENFERMOS) NUM_ENFERMOS,sum(NUM_VIVOS) NUM_VIVOS,sum(NUM_MUERTOS) NUM_MUERTOS  from
											(
												SELECT t1.* FROM traoper_055_supervivenciareforesta t1
													JOIN (SELECT ID__ESPECIE, MAX(ID_SUPERVIVENCIA) ID_SUPERVIVENCIA FROM traoper_055_supervivenciareforesta  GROUP BY ID__ESPECIE,ID__EVENTO ) t2
													ON t1.ID_SUPERVIVENCIA = t2.ID_SUPERVIVENCIA AND t1.ID__ESPECIE = t2.ID__ESPECIE
													".$whereESPECIE."
											)a
											left join catconf_065_eventos eventos on eventos.ID__EVENTO=a.ID__EVENTO
											". $where." and VCH_EMPRESASREFOR like'%\"".intval($patrocinador["ID__EMPRESA"])."\"%' ";
					$supervivenciaPatrocinador = $this->db->query($sqlSupervivenciaPatrocinador)->result_array();																													
					array_push($listaSupervivenciaPatrocinador,$supervivenciaPatrocinador[0]);
			}
			
			
		//echo "<pre>";			die(print_r($listaSupervivenciaPatrocinador));
			
								
			
			$sqlsupervivenciaPorEvento="select sum(NUM_SANOS) NUM_SANOS,sum(NUM_ENFERMOS) NUM_ENFERMOS,sum(NUM_VIVOS) NUM_VIVOS,sum(NUM_MUERTOS) NUM_MUERTOS,VCH_NOMBREEVENTO as VCH_NOMBRECOMUN  from
										(
																SELECT t1.* FROM traoper_055_supervivenciareforesta t1
																			JOIN (SELECT ID__ESPECIE, MAX(ID_SUPERVIVENCIA) ID_SUPERVIVENCIA FROM traoper_055_supervivenciareforesta  GROUP BY ID__ESPECIE,ID__EVENTO ) t2
																			ON t1.ID_SUPERVIVENCIA = t2.ID_SUPERVIVENCIA AND t1.ID__ESPECIE = t2.ID__ESPECIE
																			".$whereESPECIE."
										)a
										left join catconf_065_eventos eventos on eventos.ID__EVENTO=a.ID__EVENTO				
										group by a.ID__EVENTO
			";
			$supervivenciaPorEvento = $this->db->query($sqlsupervivenciaPorEvento,array())->result_array();						

			if($mostrarFotos){$mf="Si";} else{$mf="No";}
			if($mostrarListado){$ml="Si";} else{$ml="No";}	
			$data["mf"]=$mf;
			$data["ml"]=$ml;
			$data["nombreevento"]=$nombreevento; //
			$data["nombreempresa"]=$nombreempresa;//
			$data["nombreespecie"]=$nombreespecie;//					

			$data["cantidadEventos"]=$cantidadEventos;	
			$data["supervivencia"]=$supervivencia;		
			$data["SupervivenciaEspecie"]=$supervivenciaEspecie;	
			$data["SupervivenciaPatrocinador"]=$listaSupervivenciaPatrocinador;		
			$data["supervivenciaPorEvento"]=$supervivenciaPorEvento;		
			
			/*
			
			
						
			$sql="	select arb.ID__EVENTO,VCH_ESTADO,count(*) cant,VCH_NOMBREEVENTO from 
				(SELECT * FROM traoper_051_seguimientosresumida  group by ID__ARBOL order by  ID__SEGUIMIENTO desc) segui
				left join catconf_020_arboles arb on arb.ID__ARBOL= segui.ID__ARBOL  
				left join catconf_065_eventos eventos on eventos.ID__EVENTO=arb.ID__EVENTO
				".$where;
			$sql.="    group by arb.ID__EVENTO,VCH_ESTADO";
			$supervivenciaEvento = $this->db->query($sql)->result_array();	
			$supervivenciaEventoProcesado=array_flip(array_unique (array_column($supervivenciaEvento, 'ID__EVENTO')));
			//die(print_r($supervivenciaEventoProcesado));
			foreach($supervivenciaEvento as $supervr)
			{
					
				if(!is_array($supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]))
				{

					$supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]=array();
					$supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]["M"]=0;
					$supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]["V"]=0;
					$supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]["NOMBRE"]= $supervr["VCH_NOMBREEVENTO"];													
				}
//				
				if($supervr["VCH_ESTADO"]=="M")		
				{
					$supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]["M"]+=$supervr["cant"]					;					
				}
				if($supervr["VCH_ESTADO"]=="V")		
				{
					$supervivenciaEventoProcesado[$supervr["ID__EVENTO"]]["V"]+=$supervr["cant"]					;					
				}									
			}															
			$data["supervivenciaPorEvento"]=$supervivenciaEventoProcesado;		
//						die(print_r($supervivenciaEventoProcesado));
*/
			if($mostrarFotos)
			{
				}
			return $data;
		}
		
}


}

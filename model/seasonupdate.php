<?phpif(!$output)$output = '';$output .= '<table border=1><tr ><th colspan=3>Season '.$ep['season'].' Episodes</th></tr><tr><th>Title</th><th>Episode</th><th>Date</th><th>Saving</th><th>TotalCount</th>';$output .= '</tr>';	if($show)$episodes = $show->getSeason($ep['season']);if($episodes){foreach($episodes as $episode){	if(strtotime($episode->FirstAired)&&$episode->EpisodeName!=''){				$title = $episode->EpisodeName;		$output .= '<tr><td> '.$ep['title']=str_replace('&','and',$title);$output .= '</td>';		$output .= '<td>'.$ep['episode']=$episode->EpisodeNumber;$output .= '</td>';		$output .= '<td>' .$ep['date']=date('m/d/Y',strtotime($episode->FirstAired));	$output .= ' </td>';		$ep['showid']=$showid;		$ep['total']=$total;		if(!$getEpisodes=getEpisodes2($showid,0,$ep['episode'],$ep['season'])){			if(saveEpisode($ep,$getShow))$output .= "<td>SAVED</td>";			else $output .= "<td>ERROR SAVING</td>";		}else{			$output .= '<td>';$skip=0;			if($getEpisodes['date']!=date('m/d/Y',strtotime($episode->FirstAired))){				$skip=1;				if(updateEpisode($getEpisodes['episodeid'],'date',$value=date('m/d/Y',strtotime($episode->FirstAired))))$output .= 'UPDATED DATE ';				else $output .= 'err date ';									if(updateEpisode($getEpisodes['episodeid'],'timestamp',$value=strtotime($episode->FirstAired)))$output .= "UPDATED TIMESTAMP ";				else $output .= 'err timestamp ';			}			if($getEpisodes['total']==0||$getEpisodes['total']!=$total){				$skip=1;				if(updateEpisode($getEpisodes['episodeid'],'total',$total))	$output .= "UPDATED TOTAL ";				else $output .= 'err total';			}			if($getEpisodes['title']!=$title){				$skip=1;				if(updateEpisode($getEpisodes['episodeid'],'title',$title))$output .= "UPDATED TITLE ";				else $output .= 'err title';			}			if(!$skip) $output .= "SKIPPED";			$output .= '</td>';		}		$output .= '<td>'.$total.'</td>';		$output .= '</tr>';		$total++;	}}}$output .= '</table>';

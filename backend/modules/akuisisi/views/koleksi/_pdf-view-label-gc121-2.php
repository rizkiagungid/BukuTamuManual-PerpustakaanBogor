<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
	
	
	<?php 
	$no=0;
	$item=0;
	$rec=0;
	$jumlahData=count($LabelData);
	foreach ($LabelData as $LabelData): 
	$rec++;

	if($item == 0){
		echo '<div style="padding-top:19px; padding-left:2px; padding-bottom:0px;padding-right:2px; ">';
		echo '<table cellspacing="0" cellpadding="0">';
	}

	if($no==0)
	{
		echo '<tr>';
	}

	?>

	<td style="width:50%;padding-bottom: 10px; padding-right: 10px; text-align: left;">
		<table style="width:287px; " cellpadding="0" cellspacing="0">
			<tr>
				<td style="border:solid 1px #CCC; height:53px; width:215px; text-align: center; "><?=$LabelData['NamaPerpustakaan']?></td>
				<td style="width:25%;border-top:solid 1px #CCC;border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;text-align: center " rowspan="2"><?=str_replace(' ', '<br>', $LabelData['CallNumber'])?></td>
			</tr>
			<tr>
				<td style="height:90px; width:75%; text-align: center;padding-left: 3px; padding-right: 3px;border-left:solid 1px #CCC; border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;">
					<span style="font-size: 12px"><?=$LabelData['Title']?>
					<br>
					<?php 
					echo '<img style="padding-top:5px;width:180px;height:39px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
					?>
					<br>
					*<?=$LabelData['Barcode']?>*
					</span>
				</td>
			</tr>
		</table>
	</td>

	<?php

	if($no == 1 || $i == ($jumlahData -1))
    {
       if($i == ($jumlahData -1))
       {
            echo '<td style="width:50%;padding-bottom: 10px; padding-right: 10px; text-align: left;">&nbsp;</td>';
       }
       echo '</tr>';
       $no=0;
    }else{
       $no++;
    }

	if($item == 7 || $rec == $jumlahData)
    {
       echo '<tr><td style="height:120px" colspan="2">&nbsp;</td></tr>';
       echo '</table>';
       echo '</div>';
       $item=0;
    }else{
       $item++;
    }


	?>
	

	<?php
	endforeach; 
	?>
					
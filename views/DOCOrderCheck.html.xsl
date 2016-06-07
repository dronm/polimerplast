<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" 
 xmlns:html="http://www.w3.org/TR/REC-html40"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:fo="http://www.w3.org/1999/XSL/Format">

<xsl:import href="functions.xsl"/>

<!-- Main template-->
<xsl:template match="/">
	<xsl:apply-templates select="document/model[@id='ModelServResponse']"/>	
	<xsl:apply-templates select="document/model[@id='head']"/>		
</xsl:template>

<!-- Error -->
<xsl:template match="model[@id='ModelServResponse']">
	<xsl:if test="not(row[1]/result='0')">
	<div class="error">
		<xsl:value-of select="row[1]/descr"/>
	</div>
	</xsl:if>
</xsl:template>

<xsl:template match="model[@id='head']/row">
<!-- style="border-bottom:1px dotted black;" -->
<div style="display:inline-block;width:100%;">
	<div style="display:inline-block;width:70%;border-right:1px dotted black;padding-right:10px;">
		<h2>Товарный чек № <xsl:value-of select="number"/> от <xsl:value-of select="date_descr"/></h2>
		<!--
		<div>Организация:<xsl:value-of select="firm_descr"/></div>
		<div>Склад:<xsl:value-of select="warehouse_descr"/></div>
		<h4>Контрагент:<xsl:value-of select="client_descr"/></h4>
		-->
		<table style="width:100%;">
			<thead>
			<tr>
				<th>№</th>
				<th>Продукция</th>
				<th>Уп-ка</th>
				<th>Ед-ца</th>
				<th>Кол-во</th>
				<th>Цена</th>
				<th>Сумма</th>
			</tr>
			</thead>
			<tbody>	
			<xsl:apply-templates select="/document/model[@id='products']"/>	
			<xsl:if test="deliv_type='by_supplier' and deliv_add_cost_to_product!='t'">
				<!-- ДОСТАВКА -->
				<tr>
					<td align="center"><xsl:value-of select="count(/document/model[@id='products']/row)+1"/></td>
					<td>Доставка</td>
					<td></td>
					<td align="center">шт.</td>				
					<td align="right"><xsl:value-of select="deliv_vehicle_count"/></td><!-- quant-->
					<td align="right">
						<xsl:call-template name="format_money">
							<xsl:with-param name="val" select="deliv_total div deliv_vehicle_count"/>
						</xsl:call-template>						
					</td><!-- price-->
					<td align="right"><xsl:value-of select="deliv_total"/></td><!-- total-->
				</tr>
			</xsl:if>
			
			</tbody>	
			<tfoot align="right" style="font-weight:bolder;font-size:120%;border:none;">	
				<tr>
					<!-- ШТРИХ КОД-->
					<td colspan="3" style="border:none;">
						<div style="width:100%;text-align:center;">
							<img src="data:{barcode_img_mime};base64,{barcode_img}"/>
						</div>					
						<div style="
							font-size:60%;
							padding:0px;
							margin:5px 0px;
							width:100%;
							text-align:center;">
							<xsl:value-of select="barcode_descr"/>
						</div>
						
					</td>
					
					<td colspan="3" style="border:none;">
						<div>Вес,т.:</div>
						<div>Объем в баз.ед.:</div>
						<div>Сумма:</div>
					</td>
					<td style="border:none;">
						<div><xsl:value-of select="total_weight"/></div>
						<div><xsl:value-of select="total_quant"/></div>
						<xsl:variable name="total">
							<xsl:choose>
								<xsl:when test="deliv_type='by_supplier' and deliv_add_cost_to_product!='t'">
									<xsl:value-of select="total+deliv_total"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="total"/>
								</xsl:otherwise>
							</xsl:choose>
						</xsl:variable>
						<div><xsl:value-of select="$total"/></div>
					</td>
				</tr>
			</tfoot>
		</table>
		<br></br>
		<br></br>
		<div>
			<span>Продавец __________________</span>
			<span style="float:right;margin-right:50px;">Подпись ___________________</span>
		</div>
	</div>
	<div style="display:inline-block;width:25%;padding-left:10px;">
		<h4>Отрывной талон №<xsl:value-of select="number"/> от <xsl:value-of select="date_descr"/></h4>
		<br></br>
		<h4>Количество: <xsl:value-of select="sum(//document/model[@id='products']/row/quant)"/></h4>
		<br></br>
		<br></br>
		<br></br>
		<div>Отпустил _________________</div>
	</div>	
</div>
</xsl:template>

<xsl:template match="model[@id='products']">
	<xsl:apply-templates select="row"/>		
</xsl:template>

<xsl:template match="model[@id='products']/row">
	<tr>
		<xsl:apply-templates select="line_number"/>	
		<xsl:apply-templates select="dimen"/>	
		<xsl:apply-templates select="pack_exists"/>	
		<xsl:apply-templates select="measure_unit_descr"/>	
		<xsl:apply-templates select="quant"/>	
		<xsl:apply-templates select="price"/>	
		<xsl:apply-templates select="total"/>	
	</tr>
</xsl:template>

<xsl:template match="model[@id='products']/row/line_number">
	<td align="center"><xsl:value-of select="node()"/></td>
</xsl:template>

<xsl:template match="model[@id='products']/row/dimen">
	<td><xsl:value-of select="node()"/></td>
</xsl:template>

<xsl:template match="model[@id='products']/row/pack_exists">
	<td align="center">
	<xsl:choose>
		<xsl:when test="node()='t'">
			нет<!--<img src="img/bool/true.png"/>-->
		</xsl:when>
		<xsl:otherwise>
			есть<!--<img src="img/bool/false.png"/>-->
		</xsl:otherwise>
	</xsl:choose>
	</td>
</xsl:template>

<xsl:template match="model[@id='products']/row/measure_unit_descr">
	<td align="center"><xsl:value-of select="node()"/></td>
</xsl:template>

<xsl:template match="model[@id='products']/row/quant">
	<td align="right"><xsl:value-of select="node()"/></td>
</xsl:template>

<xsl:template match="model[@id='products']/row/price">
	<td align="right"><xsl:value-of select="node()"/></td>
</xsl:template>

<xsl:template match="model[@id='products']/row/total">
	<td align="right"><xsl:value-of select="node()"/></td>
</xsl:template>

</xsl:stylesheet>

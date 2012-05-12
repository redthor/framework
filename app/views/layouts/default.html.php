<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>
            Douglas Reith
            <?php
                $title = $this->title();
                if (!empty($title)) {
                    echo ' - ' . $title;
                }
            ?>
        </title>
	<?php echo $this->html->style(array('debug', 'lithium')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="app">
	<div id="container">
		<div id="header">
			<h1>Douglas Reith</h1>
		</div>
		<div id="content">
			<?php echo $this->content(); ?>
		</div>
	</div>
</body>
</html>

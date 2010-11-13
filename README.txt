Extend your plugin class with Tx_FluidPiBase instead of tslib_pibase.
To initialize call pi_initFluid() and then pi_setFluidView($template)
to set view template.

To assign variables to view call:
	$this->view->assign('foo', $bar);
To render view call:
	$this->view->render();

That's it.
Have fun with the awesome fluid template engine in pi based extensions.


/*
 * Example main method
 */
public function main($content, $conf) {
	$this->conf = $conf;

	$this->pi_initFluid();
	$this->pi_setFluidView($this->conf['templateFile']);

	$value = 'bar';
	$this->view->assign('foo', $value);

	return $this->pi_wrapInBaseClass($this->view->render());
}
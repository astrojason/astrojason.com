<?php
/**
 * Blade extensions.
 *
 * @link http://laravel.com/docs/4.2/templates#extending-blade
 */

use Illuminate\View\Compilers\BladeCompiler;

Blade::setContentTags('<%', '%>');
Blade::setEscapedContentTags('<%%', '%%>');

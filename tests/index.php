<?php
/**
 * Copyright (c) since 2022 FP Flavio Pellizzer
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file.
 *
 * @author Flavio Pellizzer
 * @copyright (c) since 2022 FP Flavio Pellizzer
 * @license MIT
 */
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

header('Location: ../');
exit;

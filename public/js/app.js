/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';
import 'core-js/stable';
import 'regenerator-runtime/runtime';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfMake.vfs = pdfFonts.pdfMake.vfs;

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import TablaAdvanced from '../../resources/components/TablaAdvanced';
import '../../resources/components/TablaHeader';

import React from 'react';
import ReactDOM from 'react-dom';
import SignatureForm from '../../components/SignatureForm';

ReactDOM.render(<SignatureForm />, document.getElementById('app'));
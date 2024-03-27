var ROOTHOST = 'https://hoctap.ezstudy.vn/';
var mathElements = [
'math[xmlns]',
'math',
'maction',
'maligngroup',
'malignmark',
'menclose',
'merror',
'mfenced',
'mfrac',
'mglyph',
'mi',
'mlabeledtr',
'mlogdiv',
'mlongdiv',
'mmultiscripts',
'mn',
'mo',
'mover',
'mpadded',
'mphantom',
'mroot',
'mrow',
'ms',
'mscarries',
'mscarry',
'msgroup',
'msline',
'mspace',
'msqrt',
'msrow',
'mstack',
'mstyle',
'msub',
'msup',
'msubsup',
'mtable',
'mtd',
'mtext',
'mtr',
'munder',
'munderover',
'semantics',
'annotation',
'annotation-xml'
];
var EditOption = {
    extraPlugins: 'uploadimage,uploadwidget,ckeditor_wiris,mathjax,placeholder,codesnippet,colordialog,tableresize,editorplaceholder,uploadimage,image2,font,justify',
    mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
    /*For now, MathType is incompatible with CKEditor file upload plugins.*/
    removePlugins: 'image',
    removeButtons: '',
    editorplaceholder: 'Start typing here...',
    height: 320,
    allowedContent: true,
    // filebrowserBrowseUrl: ROOTHOST + 'extensions/upload_images.php',
    filebrowserUploadUrl:ROOTHOST + 'extensions/abc.html',
    filebrowserImageUploadUrl: ROOTHOST + 'extensions/upload.php',
    /*Update the ACF configuration with MathML syntax.*/
    extraAllowedContent: mathElements.join(' ') + '(*)[*]{*};img[data-mathml,data-custom-editor,role](Wirisformula)',
    justifyClasses: [ 'AlignLeft', 'AlignCenter', 'AlignRight', 'AlignJustify' ],
}

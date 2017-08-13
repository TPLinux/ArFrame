// this will print pdf by element ID
// usage : 
// <button id="no_pdf_btn" onclick="createPDF('DivID','file.css')">Print pdf</button>
// printCSSFile : path to css file you need to apply it while printing

function createPDF(aElement, aClass = '', printCSSFile = false){
    var head = document.getElementsByTagName('head')[0].innerHTML;
    var DivToPrint = document.getElementById(aElement).innerHTML;
    // console.log(DivToPrint);
    var toPrint = window.open('','PRINT');
    toPrint.document.write("<html>");
    toPrint.document.write("<title></title>");
    toPrint.document.write("<head>");
    toPrint.document.write(head);
    if(printCSSFile != false){
	toPrint.document.write('<link href="' + printCSSFile + '" rel="stylesheet"/>');
    }
    toPrint.document.write("</head>");
    toPrint.document.write("<body>");
    toPrint.document.write("<style> #no_pdf_btn{ visibility: hidden; } </style>");
    toPrint.document.write("<div id='" + aElement + "' class='" + aClass  + "'>");
    toPrint.document.write(DivToPrint);
    toPrint.document.write("</div>");
    toPrint.document.write("</body>");
    toPrint.document.write("</html>");
    toPrint.document.close();
    toPrint.focus();
    toPrint.print();
    toPrint.close();
    
}

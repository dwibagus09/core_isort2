// modified code start
let lightPrimary  = document.querySelector('#colorID')
lightPrimary?.addEventListener('input', changePrimaryColor)

let transparentBgColorID  = document.querySelector('#transparentBgColorID')
transparentBgColorID?.addEventListener('input', transparentBgColor)

// theme color picker
const dynamicPrimaryLight = document.querySelectorAll('input.color-primary-light');
const dynamicBackground = document.querySelectorAll('input.color-bg-transparent');
dynamicPrimaryColor(dynamicPrimaryLight);
dynamicBackgroundColor(dynamicBackground);

localStorageBackup();

const handleThemeUpdate = (cssVars) => {
    const root = document.querySelector(':root');
    const keys = Object.keys(cssVars);
    keys.forEach(key => {
        root.style.setProperty(key, cssVars[key]);
    });
}

// to check the value is hexa or not
const isValidHex = (hexValue) => /^#([A-Fa-f0-9]{3,4}){1,2}$/.test(hexValue)

const getChunksFromString = (st, chunkSize) => st.match(new RegExp(`.{${chunkSize}}`, "g"))
// convert hex value to 256
const convertHexUnitTo256 = (hexStr) => parseInt(hexStr.repeat(2 / hexStr.length), 16)
// get alpha value is equla to 1 if there was no value is asigned to alpha in function
const getAlphafloat = (a, alpha) => {
    if (typeof a !== "undefined") { return a / 255 }
    if ((typeof alpha != "number") || alpha < 0 || alpha > 1) {
        return 1
    }
    return alpha
}
// convertion of hex code to rgba code
function hexToRgba(hexValue, alpha) {
    if (!isValidHex(hexValue)) { return null }
    const chunkSize = Math.floor((hexValue.length - 1) / 3)
    const hexArr = getChunksFromString(hexValue.slice(1), chunkSize)
    const [r, g, b, a] = hexArr.map(convertHexUnitTo256)
    return `rgba(${r}, ${g}, ${b}, ${getAlphafloat(a, alpha)})`
}

// convertion of hex code to rgb code
function hexToRgb(hexValue) {
    if (!isValidHex(hexValue)) { return null }
    const chunkSize = Math.floor((hexValue.length - 1) / 3)
    const hexArr = getChunksFromString(hexValue.slice(1), chunkSize)
    const [r, g, b] = hexArr.map(convertHexUnitTo256)
    return `${r}, ${g}, ${b}`
}

function dynamicPrimaryColor(primaryColor) {
    'use strict'

    primaryColor.forEach((item) => {
        item.addEventListener('input', (e) => {
            document.querySelector('html').style.setProperty('--volgh-primary-rgb', hexToRgb(e.target.value));
            document.querySelector('html').style.setProperty('--volgh-primary-rgb1', `${e.target.value}`);
        });
    });
}
function dynamicBackgroundColor(bgColor){
    bgColor.forEach((item) => {
		item.addEventListener('input', (e) => {
			const cssPropName5 = `--volgh-${e.target.getAttribute('data-id5')}`;
			const cssPropName6 = `--volgh-${e.target.getAttribute('data-id6')}`;
			handleThemeUpdate({
				[cssPropName5]: hexToRgba(e.target.value, 0.86),
				 // 95 is used as the opacity 0.95
				[cssPropName6]: e.target.value ,
			});
		});
	});
}


function changePrimaryColor() {
    let userColor = document.getElementById('colorID').value;
    localStorage.setItem('volghprimaryColor', hexToRgb(userColor));
    localStorage.setItem('volghprimaryColor1', userColor);
    names()
}

// function transparentBgColor() {
//     let userColor1 = document.getElementById('transparentBgColorID').value;
//     console.log(userColor1);
//     localStorage.setItem('volghbgColor', hexToRgba(userColor1, 0.8));
//     localStorage.setItem('volghbgwhite', userColor1);
//     localStorage.setItem('volghmenubg', userColor1);
//     localStorage.setItem('volghheaderbg', userColor1);
//     names()
//     let html = document.querySelector('html');
//     $('body').addClass('dark-mode');
//     $('body').removeClass('light-mode');
//     $('#switchbtn-dark').prop('checked', true);
//     $('#switchbtn-dark').prop('checked', true);
//     $('#switchbtn-dark').prop('checked', true);
// }

function transparentBgColor() {
	let userColor1 = document.getElementById('transparentBgColorID').value;
	localStorage.setItem('volghdarkTheme', userColor1);
	localStorage.setItem('volghdarkBody', userColor1 + "dd");
	names()
	document.querySelector('body').classList.add('dark-mode');
 	document.querySelector('body').classList.remove('light-mode')
    $('#myonoffswitch2').prop('checked', true);
    $('#myonoffswitch5').prop('checked', true);
    $('#myonoffswitch8').prop('checked', true);
 
}


// chart colors
let myVarVal, myVarVal1, myVarVal2, primaryColorVal, primaryColorVal1
export function names() {
    
    primaryColorVal = getComputedStyle(document.documentElement).getPropertyValue('--volgh-primary-rgb').trim();
    primaryColorVal1 = getComputedStyle(document.documentElement).getPropertyValue('--volgh-primary-rgb1').trim();

    //get variable
    myVarVal = localStorage.getItem("volghprimaryColor") || primaryColorVal;
    myVarVal2 = localStorage.getItem("volghprimaryColor1") || primaryColorVal1;
    myVarVal1 = localStorage.getItem("volghprimaryColor") ? hexToRgba(localStorage.getItem("volghprimaryColor"), 0.8) : null;


    if(document.querySelector('#echart1') !== null){
		echart1();
	}

}
names()


function localStorageBackup() {
    // if there is a value stored, update color picker and background color
    // Used to retrive the data from local storage
    if (localStorage.getItem("volghprimaryColor")) {
        if (document.getElementById('colorID')) {
            document.getElementById('colorID').value = localStorage.getItem("volghprimaryColor");
        }
        document.querySelector('html').style.setProperty('--volgh-primary-rgb', localStorage.getItem("volghprimaryColor"));
        document.querySelector('html').style.setProperty('--volgh-primary-rgb1', localStorage.getItem("volghprimaryColor1"));
    }

    if (localStorage.getItem("volghbgColor")) {
        if (document.getElementById('transparentBgColorID')) {
            document.getElementById('transparentBgColorID').value = localStorage.getItem("volghbgColor");
        }
        document.querySelector('html').style.setProperty('--volgh-background', localStorage.getItem("volghbgColor"));
        document.querySelector('html').style.setProperty('--volgh-white-rgb', localStorage.getItem("volghbgwhite"));
        document.querySelector('html').style.setProperty('--volgh-menu-bg', localStorage.getItem("volghmenubg"));
        document.querySelector('html').style.setProperty('--volgh-header-bg', localStorage.getItem("volghheaderbg"));
        let html = document.querySelector('html');
        $('body').addClass('dark-mode');
        if (!document.body.classList.contains('login-img')) {
            let mainHeader = document.querySelector('.app-header');
            // mainHeader.style.setProperty('--volgh-header-bg', localStorage.getItem("volghheaderbg"))
            let appSidebar = document.querySelector('.app-sidebar');
            appSidebar.style.setProperty('--volgh-white-rgb', localStorage.getItem("volghbgwhite"));
            appSidebar.style.setProperty('--volgh-menu-bg', localStorage.getItem("volghmenubg"));
        }
        $('#switchbtn-dark').prop('checked', true);
        $('#switchbtn-darkmenu').prop('checked', true);
        $('#switchbtn-darkheader').prop('checked', true);

        localStorage.removeItem("volghHeader", 'dark');
        localStorage.removeItem("volghMenu", 'dark');
        localStorage.removeItem("volghHeader", 'light');
        localStorage.removeItem("volghMenu", 'light');
    }
	if(localStorage.volghlightMode){
        document.querySelector('body').classList.remove('dark-mode');
        document.querySelector('body').classList.add('light-mode')
    }
	if(localStorage.volghdarkMode){
        document.querySelector('body').classList.add('dark-mode');
        document.querySelector('body').classList.remove('light-mode')
    }
    if(localStorage.volghhorizontal){
        document.querySelector('body').classList.add('horizontal')
    }
    if(localStorage.volghhorizontalHover){
        document.querySelector('body').classList.add('horizontal-hover')
    }
    if(localStorage.volghrtl){ 
        document.querySelector('body').classList.add('rtl');
    }
	if (localStorage.volghdarkBody) {
		document.querySelector('html').style.setProperty('--volgh-dark-theme', localStorage.volghdarkTheme);
		document.querySelector('html').style.setProperty('--volgh-dark-background', localStorage.volghdarkBody);
        document.querySelector('body').classList.add('dark-mode');
        document.querySelector('body').classList.remove('light-mode');
		$('#myonoffswitch2').prop('checked', true);
		$('#myonoffswitch5').prop('checked', true);
		$('#myonoffswitch8').prop('checked', true);
	}
	
    if (localStorage.volghclosedmenu) {
        document.querySelector('body').classList.add('closed-leftmenu');
        document.querySelector('body').classList.add('sidenav-toggled')
    }
    if (localStorage.volghicontextmenu) {
        document.querySelector('body').classList.add('icontext-menu')
        document.querySelector('body').classList.add('sidenav-toggled')
    }
    if (localStorage.volghsideiconmenu) {
        document.querySelector('body').classList.add('icon-overlay')
        document.querySelector('body').classList.add('sidenav-toggled')
    }
    if (localStorage.volghhoversubmenu) {
        document.querySelector('body').classList.add('hover-submenu')
        document.querySelector('body').classList.add('sidenav-toggled')
    }
    if (localStorage.volghhoversubmenu1) {
        document.querySelector('body').classList.add('hover-submenu1')
        document.querySelector('body').classList.add('sidenav-toggled')
    }
    if (localStorage.volghdoublemenu) {
        document.querySelector('body').classList.add('double-menu')
    }
    if (localStorage.volghdoublemenutabs) {
        document.querySelector('body').classList.add('double-menu-tabs');
    }
    if (localStorage.volghscrollable) {
        document.querySelector('body').classList.add('scrollable-layout')
    }
    if (localStorage.volghboxedwidth) {
        document.querySelector('body').classList.add('layout-boxed')
    }
    if (localStorage.volghlayoutfullwidth) {
        document.querySelector('body').classList.add('layout-fullwidth')
    }
    if (localStorage.volghlightheader) {
        document.querySelector('body').classList.add('header-light')
    }
    if (localStorage.volghcolorheader) {
        document.querySelector('body').classList.add('color-header')
    }
    if (localStorage.volghdarkheader) {
        document.querySelector('body').classList.add('dark-header')
    }
    if (localStorage.volghlightmenu) {
        document.querySelector('body').classList.add('light-menu')
    }
    if (localStorage.volghcolormenu) {
        document.querySelector('body').classList.add('color-menu')
    }
    if (localStorage.volghdarkmenu) {
        document.querySelector('body').classList.add('dark-menu')
    }
    if (localStorage.volghgradientmenu) {
        document.querySelector('body').classList.add('gradient-menu')
    }
    if (localStorage.volghdefaultlogo) {
        document.querySelector('body').classList.add('default-logo')
    }
    if (localStorage.volghcenterlogo) {
        document.querySelector('body').classList.add('center-logo')
    }
}

function echart1() {
	/*-----echart1-----*/
	var chartdata = [{
		name: 'sales',
		type: 'bar',
		data: [10, 15, 9, 18, 10, 15]
	}, {
		name: 'profit',
		type: 'line',
		smooth: true,
		data: [8, 5, 25, 10, 10]
	}, {
		name: 'growth',
		type: 'bar',
		data: [10, 14, 10, 15, 9, 25]
	}];
	var chart = document.getElementById('echart1');
	var barChart = echarts.init(chart);
	var option = {
		grid: {
			top: '6',
			right: '0',
			bottom: '17',
			left: '25',
		},
		xAxis: {
			data: ['2014', '2015', '2016', '2017', '2018'],
			axisLine: {
				lineStyle: {
					color: 'rgba(119, 119, 142, 0.2)'
				}
			},
			axisLabel: {
				fontSize: 10,
				color: '#93a1ad'
			}
		},
		tooltip: {
			show: true,
			showContent: true,
			alwaysShowContent: true,
			triggerOn: 'mousemove',
			trigger: 'axis',
			axisPointer: {
				label: {
					show: false,
				}
			}
		},
		yAxis: {
			splitLine: {
				lineStyle: {
					color: 'rgba(119, 119, 142, 0.2)'
				}
			},
			axisLine: {
				lineStyle: {
					color: 'rgba(119, 119, 142, 0.2)'
				}
			},
			axisLabel: {
				fontSize: 10,
				color: '#93a1ad'
			}
		},
		series: chartdata,
		color: ["rgb(" + myVarVal + ")", '#09ad95', '#d43f8d',]
	};
	barChart.setOption(option);
	window.addEventListener('resize',function(){
        barChart.resize();
    })
}

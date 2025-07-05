$(function (e) {
	/* Chartjs (#total-customers) */
	var myCanvas = document.getElementById("deals");
	myCanvas.height="209";

	var myCanvasContext = myCanvas.getContext("2d");
	var gradientStroke1 = myCanvasContext.createLinearGradient(0, 0, 0, 380);
	gradientStroke1.addColorStop(0, '#09ad95');
	gradientStroke1.addColorStop(1, '#09ad95');

	var myChart = new Chart(myCanvas, {
		type: 'bar',
		data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun" ,"Aug", "Sep"],
			datasets: [{
				label: 'Current Deals',
				data: [16, 14, 12, 14, 16, 15, 12, 14, 18, 10],
				backgroundColor: gradientStroke1,
				hoverBackgroundColor: gradientStroke1,
				hoverBorderWidth: 2,
				hoverBorderColor: gradientStroke1,
				barThickness: 15
			}
		  ]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tooltips: {
				mode: 'index',
				titleFontSize: 12,
				titleFontColor: '#000',
				bodyFontColor: '#000',
				backgroundColor: '#fff',
				cornerRadius: 3,
				intersect: false,
			},
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				x: {
					display: true,
				  grid: {
					display: false
				  },
				  ticks: {
                    color: "#77778e",
                  },
				},
				y: {
					display: false,
				  grid: {
					display: false
				  }
				}
			  },
			title: {
				display: false,
				text: 'Normal Legend'
			},
		}
	});


	
	
	/* Chartjs (#total-customers) closed */

	/* Chartjs (#total-coversations) */


	 /*LIne-Chart */
	 var ctx = document.getElementById("total-coversations").getContext('2d');
	 var myChart = new Chart(ctx, {
		 type: 'line',
		 data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
			 datasets: [{
				label: "Total-Transactions",
				data: [0, 50, 0, 100, 50, 130, 100, 140, 50, 0, 100, 50, 130, 100, 140],
				 backgroundColor: 'transparent',
				 borderColor: '#0774f8',
				 borderWidth: 4,
				 lineTension:0.3,
				 pointBackgroundColor: '#0774f8',
				 pointRadius: 3
			 }]
		 },
		 options: {
			 responsive: true,
			 maintainAspectRatio: false,
 
			 plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				x: {
					display: true,
				  grid: {
					display: true,
					color: 'rgba(119, 119, 142, 0.2)',
				  },
				  
				  ticks: {
                    color: "#77778e",
                  },
				},
				y: {
					
					color: 'rgba(119, 119, 142, 0.2)',
				  grid: {
					display: false
				  },
				  
				  ticks: {
                    color: "#77778e",
                  },
				}
			  },
		 }
	 });
	/* Chartjs (#total-coversations) closed */


	/*---- morrisBar8----*/
	new Morris.Donut({
		element: 'morrisBar8',
		data: [{
			value: 23,
			label: 'Excellent'
		}, {
			value: 16,
			label: 'Good'
		},  {
			value: 10,
			label: 'Average'
		}, {
			value: 15,
			label: 'Bad'
		}],
		backgroundColor: 'rgba(119, 119, 142, 0.1)',
		labelColor: '#77778e',
		colors: ['#0774f8', '#d43f8d', '#09ad95',  '#f5334f'],
		formatter: function(x) {
			return x + "%"
		},
		resize: true,
	}).on('click', function(i, row) {
		console.log(i, row);
	});


	/* Chartjs (#revenue) */
	var myCanvas = document.getElementById("revenue");
	myCanvas.height="300";
	var myCanvasContext = myCanvas.getContext("2d");
	var myChart = new Chart(myCanvas, {
		type: 'bar',
		data: {
			labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
			datasets: [{
				label: 'total profit',
				data: [15, 18, 12, 14, 10, 15, 7, 14],
					backgroundColor:'#d43f8d',
					hoverBackgroundColor: '#d43f8d',
					hoverBorderWidth: 2,
					hoverBorderColor: '#d43f8d',
					barPercentage: 0.5,
			}, {

			    label: 'Total sales',
				data: [10, 14, 10, 15, 9, 14, 15, 20],
					backgroundColor: '#0774f8',
					hoverBackgroundColor:'#0774f8',
					hoverBorderWidth: 3,
					hoverBorderColor: '#0774f8',
					barPercentage: 0.5,
			}
		  ]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tooltips: {
				mode: 'index',
				titleFontSize: 12,
				titleFontColor: '#000',
				bodyFontColor: '#000',
				backgroundColor: '#fff',
				cornerRadius: 3,
				intersect: false,
			},
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				x: {
				  grid: {
					display: true,
					color: 'rgba(119, 119, 142, 0.2)',
				  },
				  
				  ticks: {
                    color: "#77778e",
                  },
				},
				y: {
				  grid: {
					display: true,
					color: 'rgba(119, 119, 142, 0.2)',
				  },
				  
				  ticks: {
                    color: "#77778e",
                  },
				}
			  },
			title: {
				display: false,
				text: 'Normal Legend'
			}
		}
	});
	/* Chartjs (#revenue) closed */


	/* Chartjs (#areaChart1) */
	
	var myCanvas = document.getElementById("areaChart1");
	myCanvas.height="300";
	var myCanvasContext = myCanvas.getContext("2d");
	var myChart = new Chart(myCanvas, {
		type: 'line',
		data: {
			labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
			datasets: [{
				label: 'Market value',
				data: [30, 70, 30, 100, 50, 130, 100, 140],
				backgroundColor: 'transparent',
							borderColor: '#d43f8d',
							pointBackgroundColor:'#fff',
							pointHoverBackgroundColor:'#d43f8d',
							pointBorderColor :'#d43f8d',
							pointHoverBorderColor :'#d43f8d',
							pointBorderWidth :2,
							lineTension:0.3,
							pointRadius :2,
							pointHoverRadius :2,
							borderWidth: 4
			},
		  ]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tooltips: {
				mode: 'index',
				titleFontSize: 12,
				titleFontColor: '#000',
				bodyFontColor: '#000',
				backgroundColor: '#fff',
				cornerRadius: 3,
				intersect: false,
			},
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				x: {
					display: false,
				  grid: {
					display: false
				  }
				},
				y: {
					display: false,
				  grid: {
					display: false
				  }
				}
			  },
			title: {
				display: false,
				text: 'Normal Legend'
			}
		}
	});


	/* Chartjs (#areaChart1) closed */

	/* Chartjs (#areaChart2) */
	

	var myCanvas = document.getElementById("areaChart2");
	myCanvas.height="300";
	var myCanvasContext = myCanvas.getContext("2d");
	var myChart = new Chart(myCanvas, {
		type: 'line',
		data: {
			labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
			datasets: [{
				label: 'Market value',
				data: [24, 18, 28, 21, 32, 28,30],
				backgroundColor: 'transparent',
				borderColor: '#09ad95',
				pointBackgroundColor:'#fff',
				pointHoverBackgroundColor:'#09ad95',
				pointBorderColor :'#09ad95',
				pointHoverBorderColor :'#09ad95',
				pointBorderWidth :2,
				lineTension:0.3,
				pointRadius :2,
				pointHoverRadius :2,
				borderWidth: 4
			},
		  ]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tooltips: {
				mode: 'index',
				titleFontSize: 12,
				titleFontColor: '#000',
				bodyFontColor: '#000',
				backgroundColor: '#fff',
				cornerRadius: 3,
				intersect: false,
			},
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				x: {
					display: false,
				  grid: {
					display: false
				  }
				},
				y: {
					display: false,
				  grid: {
					display: false
				  }
				}
			  },
			title: {
				display: false,
				text: 'Normal Legend'
			}
		}
	});
	/* Chartjs (#areaChart2) closed */



});






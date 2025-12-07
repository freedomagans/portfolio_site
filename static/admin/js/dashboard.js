// Chart.js Initialization with Real Data
document.addEventListener('DOMContentLoaded', function() {

    // Chart defaults
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;

    // Prepare chart data from window
    const viewsData = window.chartData.viewsData;
    const likesData = window.chartData.likesData;
    const commentsData = window.chartData.commentsData;
    const monthLabels = window.chartData.monthLabels;

    // Analytics Overview Line Chart
    const analyticsCtx = document.getElementById('analyticsChart');
    new Chart(analyticsCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Views',
                    data: viewsData,
                    borderColor: 'rgb(79, 172, 254)',
                    backgroundColor: 'rgba(79, 172, 254, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 3
                },
                {
                    label: 'Likes',
                    data: likesData,
                    borderColor: 'rgb(240, 147, 251)',
                    backgroundColor: 'rgba(240, 147, 251, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 3
                },
                {
                    label: 'Comments',
                    data: commentsData,
                    borderColor: 'rgb(17, 153, 142)',
                    backgroundColor: 'rgba(17, 153, 142, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat().format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return new Intl.NumberFormat().format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Engagement Distribution Doughnut Chart
    const engagementCtx = document.getElementById('engagementChart');
    const totalViews = window.chartData.totalViews;
    const totalLikes = window.chartData.totalLikes;
    const totalComments = window.chartData.totalComments;
    const totalMessages = window.chartData.totalMessages;

    new Chart(engagementCtx, {
        type: 'doughnut',
        data: {
            labels: ['Views', 'Likes', 'Comments', 'Messages'],
            datasets: [{
                data: [totalViews, totalLikes, totalComments, totalMessages],
                backgroundColor: [
                    'rgba(79, 172, 254, 0.8)',
                    'rgba(240, 147, 251, 0.8)',
                    'rgba(17, 153, 142, 0.8)',
                    'rgba(250, 112, 154, 0.8)'
                ],
                borderColor: [
                    'rgb(79, 172, 254)',
                    'rgb(240, 147, 251)',
                    'rgb(17, 153, 142)',
                    'rgb(250, 112, 154)'
                ],
                borderWidth: 3,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${new Intl.NumberFormat().format(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Monthly Activity Bar Chart
    const activityCtx = document.getElementById('activityChart');
    const projectsData = window.chartData.projectsData;
    const messagesData = window.chartData.messagesData;

    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Projects',
                    data: projectsData,
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgb(102, 126, 234)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Comments',
                    data: commentsData,
                    backgroundColor: 'rgba(17, 153, 142, 0.8)',
                    borderColor: 'rgb(17, 153, 142)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Messages',
                    data: messagesData,
                    backgroundColor: 'rgba(250, 112, 154, 0.8)',
                    borderColor: 'rgb(250, 112, 154)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat().format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        stepSize: 1,
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Add smooth animations to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    statCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });

});

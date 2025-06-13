// Admin Dashboard JavaScript
class AdminDashboard {
    constructor() {
        this.currentPage = 1;
        this.perPage = 10;
        this.currentFilter = 'all';
        this.initializeEventListeners();
        this.loadInitialData();
    }

    initializeEventListeners() {
        // Activity filter change
        $('#activity-filter').on('change', (e) => {
            this.currentFilter = e.target.value;
            this.updateActivityTable(1);
        });

        // Pagination buttons
        $('#prev-page, #prev-page-mobile').on('click', () => {
            if (this.currentPage > 1) {
                this.updateActivityTable(this.currentPage - 1);
            }
        });

        $('#next-page, #next-page-mobile').on('click', () => {
            this.updateActivityTable(this.currentPage + 1);
        });

        // Refresh data every 5 minutes
        setInterval(() => this.loadInitialData(), 300000);
    }

    loadInitialData() {
        this.updateDashboardStats();
        this.updateActivityTable(this.currentPage);
    }

    showError(message) {
        // Create toast notification
        const toast = $(`
            <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-transform duration-300 translate-y-full">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>${message}</span>
                </div>
            </div>
        `).appendTo('body');

        // Animate in
        setTimeout(() => toast.removeClass('translate-y-full'), 100);

        // Remove after 5 seconds
        setTimeout(() => {
            toast.addClass('translate-y-full');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    updateDashboardStats() {
        $.ajax({
            url: '/admin/stats',
            method: 'GET',
            success: (response) => {
                $('#total-users').text(response.totalUsers.toLocaleString());
                $('#new-users').text(response.newUsers.toLocaleString());
                $('#active-sessions').text(response.activeSessions.toLocaleString());
                $('#recent-logins').text(response.recentLogins.toLocaleString());
            },
            error: (xhr) => {
                console.error('Error fetching dashboard stats:', xhr);
                this.showError('Error loading dashboard statistics');
            }
        });
    }

    updateActivityTable(page = 1) {
        this.currentPage = page;
        const tbody = $('#activity-table-body');

        // Show loading state
        tbody.html(this.getLoadingTemplate());

        $.ajax({
            url: '/admin/activities',
            method: 'GET',
            data: {
                page: page,
                per_page: this.perPage,
                filter: this.currentFilter
            },
            success: (response) => {
                const { data, total, current_page, last_page } = response;

                // Update pagination info
                this.updatePaginationInfo(current_page, total);

                // Update table content
                if (data.length === 0) {
                    tbody.html(this.getEmptyStateTemplate());
                } else {
                    tbody.html(this.renderActivityRows(data));
                }

                // Update pagination buttons state
                this.updatePaginationButtons(current_page, last_page);
            },
            error: (xhr) => {
                console.error('Error fetching activities:', xhr);
                this.showError('Error loading activities');
                tbody.html(this.getErrorTemplate());
            }
        });
    }

    getLoadingTemplate() {
        return `
            <tr>
                <td colspan="4" class="px-6 py-4 text-center">
                    <div class="flex justify-center items-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-500">Loading activities...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    getEmptyStateTemplate() {
        return `
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-medium">No activities found</p>
                        <p class="text-sm">Try changing your filter or check back later</p>
                    </div>
                </td>
            </tr>
        `;
    }

    getErrorTemplate() {
        return `
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-red-500">
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg font-medium">Error loading activities</p>
                        <p class="text-sm">Please try again later</p>
                    </div>
                </td>
            </tr>
        `;
    }

    renderActivityRows(activities) {
        return activities.map(activity => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full" src="${activity.user.avatar || '/images/default-avatar.png'}" alt="">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${activity.user.name}</div>
                            <div class="text-sm text-gray-500">${activity.user.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${this.getActivityBadgeClass(activity.type)}">
                        ${activity.type}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${activity.details}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${this.formatDate(activity.created_at)}
                </td>
            </tr>
        `).join('');
    }

    getActivityBadgeClass(type) {
        const classes = {
            'login': 'bg-green-100 text-green-800',
            'rental': 'bg-blue-100 text-blue-800',
            'payment': 'bg-purple-100 text-purple-800',
            'default': 'bg-gray-100 text-gray-800'
        };
        return classes[type.toLowerCase()] || classes.default;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }

    updatePaginationInfo(currentPage, total) {
        const start = (currentPage - 1) * this.perPage + 1;
        const end = Math.min(currentPage * this.perPage, total);

        $('#pagination-start').text(start);
        $('#pagination-end').text(end);
        $('#pagination-total').text(total);
    }

    updatePaginationButtons(currentPage, lastPage) {
        const prevButtons = $('#prev-page, #prev-page-mobile');
        const nextButtons = $('#next-page, #next-page-mobile');

        prevButtons.prop('disabled', currentPage === 1)
            .toggleClass('opacity-50 cursor-not-allowed', currentPage === 1)
            .toggleClass('hover:bg-gray-50', currentPage !== 1);

        nextButtons.prop('disabled', currentPage === lastPage)
            .toggleClass('opacity-50 cursor-not-allowed', currentPage === lastPage)
            .toggleClass('hover:bg-gray-50', currentPage !== lastPage);
    }
}

// Initialize dashboard when document is ready
$(document).ready(() => {
    window.adminDashboard = new AdminDashboard();
});

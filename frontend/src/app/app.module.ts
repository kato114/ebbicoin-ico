import { BrowserModule } from '@angular/platform-browser'
import { NgModule } from '@angular/core'
import { RouterModule, Routes } from '@angular/router'
import { FormsModule, ReactiveFormsModule } from '@angular/forms'
import { FieldErrorDisplayComponent } from './field-error-display/field-error-display.component'
import { HttpModule } from '@angular/http'
import { HttpClientModule } from '@angular/common/http'
import { Ng4LoadingSpinnerModule } from 'ng4-loading-spinner'
import { DataTablesModule } from 'angular-datatables'
import { Ng2SearchPipeModule } from 'ng2-search-filter'

// components
import { AppComponent } from './app.component'
import { HomeComponent } from './home/home.component'
import { RegisterComponent } from './register/register.component'

// user component
import { HeaderComponent } from './common/header/header.component'
import { SidebarComponent } from './common/sidebar/sidebar.component'
import { FooterComponent } from './common/footer/footer.component'
import { DashboardComponent } from './dashboard/dashboard.component'
import { TeamMembersComponent } from './team-members/team-members.component'
import { SupportsListComponent } from './supports/list/list-tickets.component'
import { SupportsAddComponent } from './supports/add/add-ticket.component'
import { SupportsViewComponent } from './supports/view/view-tickets.component'
import { MyWalletsComponent } from './my-wallets/my-wallets.component'
import { MyAccountComponent } from './my-account/my-account.component'
import { MakeMoneyComponent } from './make-money/make-money.component'
import { HowToBuyComponent } from './how-to-buy/how-to-buy.component'

// admin component
import { AdminLoginComponent } from './admin/login/login.component'
import { AdminHeaderComponent } from './admin/common/header/header.component'
import { AdminSidebarComponent } from './admin/common/sidebar/sidebar.component'
import { AdminFooterComponent } from './admin/common/footer/footer.component'
import { AdminDashboardComponent } from './admin/dashboard/dashboard.component'
import { UsersListComponent } from './admin/users/list/user-list.component'
import { AdminUserAddComponent } from './admin/users/add/user-add.component'
import { AdminUserEditComponent } from './admin/users/edit/user-edit.component'
import { AdminSupportsListComponent } from './admin/supports/list/list-tickets.component'
import { AdminSupportsViewComponent } from './admin/supports/view/view-tickets.component'
import { AdminChangePasswordComponent } from './admin/change-password/change-password.component'

// services
import { HomeService } from './home/home.service'
import { HeaderService } from './common/header/header.service'
import { DashboardService } from './dashboard/dashboard.service'
import { MyWalletsService } from './my-wallets/my-wallets.service'
import { MyAccountService } from './my-account/my-account.service'
import { SupportService } from './supports/support.service'
import { TeamMembersService } from './team-members/team-members.service'

// admin services
import { AdminLoginService } from './admin/login/login.service'
import { AdminCommonService } from './admin/common/admin-common.service'
import { AdminUsersService } from './admin/users/users.service'
import { AdminSupportService } from './admin/supports/support.service'
import { AdminRoundService } from './admin/round/round.service'
import { AdminRoundComponent } from './admin/round/round.component'

const appRoutes: Routes = [
	// home routes
	{ path: '', component: HomeComponent },
	{ path: 'home', component: HomeComponent },
	{ path: 'home/login', component: HomeComponent },
	{ path: 'register/:referral', component: RegisterComponent },

	// user routes
	{ path: 'dashboard', component: DashboardComponent },
	{ path: 'how-to-buy', component: HowToBuyComponent },
	{ path: 'make-money', component: MakeMoneyComponent },
	{ path: 'my-account', component: MyAccountComponent },
	{ path: 'my-wallets', component: MyWalletsComponent },
	{ path: 'supports', component: SupportsListComponent },
	{ path: 'support/add', component: SupportsAddComponent },
	{ path: 'support/view/:id', component: SupportsViewComponent },
	{ path: 'team-members', component: TeamMembersComponent },

	// admin routes
	{ path: 'admin', component: AdminLoginComponent },
	{ path: 'admin/dashboard', component: AdminDashboardComponent },
	{ path: 'admin/users', component: UsersListComponent },
	{ path: 'admin/user/add', component: AdminUserAddComponent },
	{ path: 'admin/user/edit/:id', component: AdminUserEditComponent },
	{ path: 'admin/supports', component: AdminSupportsListComponent },
	{ path: 'admin/support/view/:id', component: AdminSupportsViewComponent },
	{ path: 'admin/change-password', component: AdminChangePasswordComponent },
	{ path: 'admin/round/:stage', component: AdminRoundComponent },

	// 404 route
	{ path: '**', redirectTo: '' },
]

@NgModule({
	declarations: [
		AppComponent,

		// home component
		HomeComponent,
		RegisterComponent,

		// user component
		HeaderComponent,
		FooterComponent,
		SidebarComponent,
		DashboardComponent,
		HowToBuyComponent,
		MakeMoneyComponent,
		MyAccountComponent,
		MyWalletsComponent,
		SupportsListComponent,
		SupportsAddComponent,
		SupportsViewComponent,
		TeamMembersComponent,

		// Admin Components
		AdminLoginComponent,
		AdminHeaderComponent,
		AdminFooterComponent,
		AdminSidebarComponent,
		AdminDashboardComponent,
		UsersListComponent,
		AdminUserAddComponent,
		AdminUserEditComponent,
		AdminSupportsListComponent,
		AdminSupportsViewComponent,
		AdminChangePasswordComponent,
		AdminRoundComponent,

		// error component
		FieldErrorDisplayComponent,
	],

	imports: [BrowserModule, FormsModule, ReactiveFormsModule, HttpModule, HttpClientModule, Ng4LoadingSpinnerModule.forRoot(), RouterModule.forRoot(appRoutes), DataTablesModule, Ng2SearchPipeModule],

	providers: [
		// home service
		HomeService,

		// user services
		HeaderService,
		DashboardService,
		MyWalletsService,
		MyAccountService,
		SupportService,
		TeamMembersService,

		// admin services
		AdminLoginService,
		AdminCommonService,
		AdminUsersService,
		AdminSupportService,
		AdminRoundService,
	],

	bootstrap: [AppComponent],
})
export class AppModule {}

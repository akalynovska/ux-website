import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeComponent } from './home/home.component';
import { AboutComponent} from './about/about.component';
import { ShowcasesComponent} from './showcases/showcases.component';
import { ContactComponent} from './contact/contact.component';


const routes: Routes = [
    { path: '', redirectTo: 'home', pathMatch: 'full' },
    { path: 'home', component: HomeComponent },
    { path: 'about', component: AboutComponent },
    { path: 'showcases', component: ShowcasesComponent },
    { path: 'contact', component: ContactComponent },
    { path: 'admin', loadChildren: 'app/admin/admin.module#AdminModule' }
];

export const routing: ModuleWithProviders = RouterModule.forRoot(routes);

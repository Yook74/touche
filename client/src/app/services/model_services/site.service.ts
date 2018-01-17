import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Site } from '../../models/site';

const mockSites: Site[] = [
    {
        id: 1,
        name: 'Site 1',
        location: 'somewhere',
        teamIds: 1,
        status: 'off'
    },
    {
        id: 2,
        name: 'Site 2',
        location: 'somewhere',
        teamIds: 2,
        status: 'on'
    }
]

@Injectable()
export class SiteService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockSites;
    }

    getSites() {
        return this.baseService.get('');
    }

    createSite(site: Site) {
        return this.baseService.post('', site);
    }

    updateSite(site: Site) {
        return this.baseService.put('', site);
    }

    deleteSite(site: number) {
        return this.baseService.delete('');
    }
}

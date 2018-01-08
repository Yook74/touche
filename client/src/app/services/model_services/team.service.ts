import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Team } from '../../models/team';

@Injectable()
export class TeamService {
    constructor(private baseService: BaseService) { }
}
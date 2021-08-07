import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {NgbDropdown} from "@ng-bootstrap/ng-bootstrap";
import {AuthService, UtilsService} from "../../service";
import * as md5 from 'md5';
import {Router} from "@angular/router";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  expanded : boolean = false;
  gravatar: string;
  email: string;

  @ViewChild('myDrop') drop: any;
  constructor(
    private utils: UtilsService,
    private auth: AuthService,
    private router: Router,
    private http: HttpClient,
    ) {}

  ngOnInit(): void {
    this.setEmail();
  }

  toggle(): void {
    this.expanded = !this.expanded;
    if(this.expanded) {
      this.drop.open();
    } else {
      this.drop.close();
    }
  }

  setting(): void {
    this.expanded = false;
    this.drop.close();
    this.router.navigate(['reglages'])
  }

  setEmail(): void {
    const jwt = this.utils.parseJwt(this.auth.getUserToken());
    this.email = jwt.email;
    const emailMd5 = md5(this.email.toLowerCase());
    console.log( emailMd5 );
    this.gravatar = 'https://www.gravatar.com/avatar/' + emailMd5;
  }

  unlog(): void {
    this.http.get('/api/unlog').subscribe(() => {
      this.auth.unlog();
      this.router.navigate(['/connexion']);
      this.drop.close();
      this.expanded = false;
    });
  }

}

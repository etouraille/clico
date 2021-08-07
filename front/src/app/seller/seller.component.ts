import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {Shop} from "@shared";
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-seller',
  templateUrl: './seller.component.html',
  styleUrls: ['./seller.component.css']
})
export class SellerComponent implements OnInit {

  active: boolean = true;
  shop: Shop;

  constructor(
    private router: Router,
    private http: HttpClient,
  ) { }

  ngOnInit(): void {
    this.setCurrentShop();
  }

  toggle() :void {
    this.active = !this.active;
  }

  navigate(link: string): void {
    switch( link ) {
      case 'home' :
        this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid]);
        break;
      case 'product' :
        this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/produits']);
        break;
      case 'variant' :
        this.router.navigate(['je-vends/ma-boutique/' + this.shop.uuid + '/liste-des-variants']);
        break;
    }

  }

 setCurrentShop() : void {
    this.http.get<Shop[]>('/api/shops').subscribe((shops: Shop[]) => {
      this.shop = shops.length>0 ?shops[0] : null;
    })
  }
}

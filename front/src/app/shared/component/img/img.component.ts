import {AfterViewInit, Component, Input, OnChanges, OnInit} from '@angular/core';
import {environment} from "../../../../environments/environment";

@Component({
  selector: 'app-img',
  templateUrl: './img.component.html',
  styleUrls: ['./img.component.css']
})
export class ImgComponent implements OnInit, OnChanges {

  @Input() file: string;
  @Input() size: string = 'profile' // 'upload' || 'vignette'

  src : string;
  cdn = environment.production ? 'https://mycdn:8081/file' : 'http://localhost:8081/file';
  constructor() { }

  ngOnInit(): void {
    this.setSrc();
  }

  ngOnChanges() {
    this.setSrc();
  }

  setSrc(): void {
    const radical = this.file.substr(0, this.file.lastIndexOf('.'));
    switch(this.size) {
      case 'upload' :
        this.src = this.cdn + '/' + radical + '.png';
        break;
      case 'vignette' :
        this.src = this.cdn + '/' + radical + '-vignette.png';
        break;
      default :
        this.src = this.cdn + '/' + radical + '-profile.png';
        break;
    }
  }
}

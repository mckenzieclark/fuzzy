/**
 * Fuzzy plugin for Craft CMS
 *
 * Fuzzy JS
 *
 * @author    John Clark
 * @copyright Copyright (c) 2020 John Clark
 * @link      https://github.com/mckenzieclark
 * @package   Fuzzy
 * @since     1.0.0
 */

let fields = [];

const config = {
  fields: [
    {
      handle: 'events',
      display: false
    }
  ]
}

var Field = function() {
  this.init = (config) => {
    this.handle = config.handle;
    this.display = config.display;
    this.parent = document.getElementById(`fields-${config.handle}`);
    this.els = this.parent.getElementsByClassName('element');
    return this;
  }
}

Field.prototype.toggle = function() {
  Array.from(this.els).forEach((el) => {
    el.style.display = this.display ? 'block' : 'none';
  });
}

config.fields.forEach(h => {
  let field = new Field();
  fields.push(field.init(h));
  field.toggle();
});

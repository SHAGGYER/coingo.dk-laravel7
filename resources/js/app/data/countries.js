import countries from "countries-list/dist/countries.min";

let countriesArray = [];

for (let key in countries) {
  if (countries.hasOwnProperty(key)) {
    countriesArray.push({
      ...countries[key],
      key
    });
  }
}

export default countriesArray;

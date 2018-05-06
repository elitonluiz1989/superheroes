const api_url = window.location.origin + '/superheroes';
const photos_path = 'storage/photos/';
const photo_anonymous  = photos_path + 'anonymous.png';

export const CONFIG = {
    API_URL: api_url,
    PHOTOS: {
        PATH: photos_path,
        DEFAULT: photo_anonymous
    },
    REQUEST_MESSAGE_ON_LOG: true
};
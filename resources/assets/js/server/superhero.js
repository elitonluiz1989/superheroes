import {CONFIG} from '../config';

export default {
    url: CONFIG.API_URL,

    load(id) {
        let url = this.url;
        if (id) {
             url += '/' + id;
        }

        return axios.get(url);
    },

    add(schedule) {
        return axios.post(this.url, schedule);
    },

    edit(schedule) {
        return axios.put(this.url, schedule);
    },

    del(id) {
        let url = this.url + '/delete/';

        let data = {
            params: {
                id: id
            }
        };

        return axios.delete(url, data);
    },

    deleteImages(images) {
        let url = this.url + '/images/delete/';

        let data = {
            params: {
                images: images
            }
        };

        return axios.delete(url, data);
    },

    upload(image, uploadProgressEvent) {
        let configs = {
            headers: { 'content-type': 'multipart/form-data' }
        };

        if (uploadProgressEvent) {
            configs.onUploadProgress = uploadProgressEvent;
        }

        let data = new FormData();
        data.append('image', image);

        return axios.post(this.url + '/images/upload', data, configs)
    }
}
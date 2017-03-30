import React from 'react';
import {render} from 'react-dom';
import {Button, Modal, ModalHeader, ModalBody, ModalFooter} from 'reactstrap';

export default class ArticleForm extends React.Component {
  constructor(props) {
    super(props);


    this.handleTitleChange = this.handleTitleChange.bind(this);
    this.state = this.props.article;
  }

  handleTitleChange(e) {
    this.setState({
      title: e.target.value
    });
  }

  render() {
    let article = this.state;
    return <Modal isOpen={this.props.modal} toggle={this.props.toggle} className={this.props.className}>
      <ModalHeader toggle={this.toggle}>
        { article.id ? `Editing ${article.title}` : 'Add Article' }
      </ModalHeader>
      <ModalBody>
        <form>
          <div className="form-group">
            <label htmlFor="title">Title</label>
            <input
              type="text"
              className="form-control"
              value={article.title}
              onChange={this.handleTitleChange}/>
          </div>
        </form>
      </ModalBody>
      <ModalFooter>
        <Button color="primary" onClick={this.props.toggle}>Do Something</Button>{' '}
        <Button color="secondary" onClick={this.props.toggle}>Cancel</Button>
      </ModalFooter>
    </Modal>
  }
}